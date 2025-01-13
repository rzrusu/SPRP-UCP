<?php
/**
 *	This API connects directly to the server, without any need for any
 *	middlemen connections.
 *	Your server must have fsockopen enabled in order to access the
 *	functions that have been made available from this.
 *
 *	@package sampAPI
 *	@version 1.2
 *	@author David Weston <westie@typefish.co.uk>
 *	@copyright 2010; http://www.typefish.co.uk/licences/
 */
namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class SampQueryAPI
{
    /**
     *	@ignore
     */
    private $rSocket = false;


    /**
     *	@ignore
     */
    private $aServer = array();


    /**
     *	Creation of the server class.
     *
     *	@param string $sServer Server IP, or hostname.
     *	@param integer $iPort Server port
     */
    public function __construct($sServer, $iPort = 7777)
    {
        /* Fill some arrays. */
        $this->aServer[0] = $sServer;
        $this->aServer[1] = $iPort;

        /* Start the connection. */
        $this->rSocket = fsockopen('udp://'.$this->aServer[0], $this->aServer[1], $iError, $sError, 2);

        if(!$this->rSocket)
        {
            $this->aServer[4] = false;
            return;
        }

        socket_set_timeout($this->rSocket, 2);

        $sPacket = 'SAMP';
        $sPacket .= chr(strtok($this->aServer[0], '.'));
        $sPacket .= chr(strtok('.'));
        $sPacket .= chr(strtok('.'));
        $sPacket .= chr(strtok('.'));
        $sPacket .= chr($this->aServer[1] & 0xFF);
        $sPacket .= chr($this->aServer[1] >> 8 & 0xFF);
        $sPacket .= 'p4150';

        fwrite($this->rSocket, $sPacket);

        if(fread($this->rSocket, 10))
        {
            if(fread($this->rSocket, 5) == 'p4150')
            {
                $this->aServer[4] = true;
                return;
            }
        }

        $this->aServer[4] = false;
    }


    /**
     *	@ignore
     */
    public function __destruct()
    {
        @fclose($this->rSocket);
    }


    /**
     *	Used to tell if the server is ready to accept queries.
     *
     *	If false is returned, then it is suggested that you remove the
     *	class from active use, so that you can reload the class if needs
     *	be.
     *
     *	@return bool true if success, false if failure.
     */
    public function isOnline()
    {
        return isset($this->aServer[4]) ? $this->aServer[4] : false;
    }


    /**
     *	This function is used to get the server information.
     *
     *	<code>
     *	Array
     *	(
     *		[password] => 0
     *		[players] => 9
     *		[maxplayers] => 500
     *		[hostname] => Everystuff Tr3s [MAD]oshi (03a Final) [FIXED]
     *		[gamemode] => Stunt/Race/DM/FR Everystuff
     *		[mapname] => Everystuff
     *	)
     *	</code>
     *
     *	@return array Array of server information.
     */
    public function getInfo()
    {
        @fwrite($this->rSocket, $this->createPacket('i'));

        fread($this->rSocket, 11);

        $aDetails['password'] = (integer) ord(fread($this->rSocket, 1));

        $aDetails['players'] = (integer) $this->toInteger(fread($this->rSocket, 2));

        $aDetails['maxplayers'] = (integer) $this->toInteger(fread($this->rSocket, 2));

        $iStrlen = ord(fread($this->rSocket, 4));
        if(!$iStrlen) return -1;

        $aDetails['hostname'] = (string) fread($this->rSocket, $iStrlen);

        $iStrlen = ord(fread($this->rSocket, 4));
        $aDetails['gamemode'] = (string) fread($this->rSocket, $iStrlen);

        $iStrlen = ord(fread($this->rSocket, 4));
        $aDetails['mapname'] = (string) fread($this->rSocket, $iStrlen);

        return $aDetails;
    }


    /**
     *	This function gets a basic list of all the players on the server.
     *
     *	Note as of 0.3.0, the amount of players that can be retrieved is
     *	limited to 100. This means if there are more players than 100,
     *	then no data will be returned, and it will be a blank array.
     *
     *	<code>
     *	Array
     *	(
     *		[0] => Array
     *			(
     *				[nickname] => K1nNngO
     *				[score] => 72
     *			)
     *
     *		[1] => Array
     *			(
     *				[nickname] => [kikOo]
     *				[score] => 150
     *			)
     *
     *		[and so on...]
     *	)
     *	</code>
     *
     *	@return array Array of player information.
     */
    public function getBasicPlayers()
    {
        @fwrite($this->rSocket, $this->createPacket('c'));
        fread($this->rSocket, 11);

        $iPlayerCount = ord(fread($this->rSocket, 2));
        $aDetails = array();

        if($iPlayerCount > 0)
        {
            for($iIndex = 0; $iIndex < $iPlayerCount; ++$iIndex)
            {
                $iStrlen = ord(fread($this->rSocket, 1));
                $aDetails[] = array
                (
                    "nickname" => (string) fread($this->rSocket, $iStrlen),
                    "score" => (integer) $this->toInteger(fread($this->rSocket, 4)),
                );
            }
        }

        return $aDetails;
    }


    /**
     *	This function gets a detailed list of all the players on the server.
     *
     *	Note as of 0.3.0, the amount of players that can be retrieved is
     *	limited to 100. This means if there are more players than 100,
     *	then no data will be returned, and it will be a blank array.
     *
     *	<code>
     *	Array
     *	(
     *		[0] => Array
     *			(
     *				[playerid] => 0
     *				[nickname] => K1nNngO
     *				[score] => 72
     *				[ping] => 195
     *			)
     *
     *		[1] => Array
     *			(
     *				[playerid] => 1
     *				[nickname] => [kikOo]
     *				[score] => 150
     *				[ping] => 375
     *			)
     *
     *		[and so on...]
     *	)
     *	</code>
     *
     *	@return array Array of player information.
     */
    public function getDetailedPlayers()
    {
        @fwrite($this->rSocket, $this->createPacket('d'));
        fread($this->rSocket, 11);

        $iPlayerCount = ord(fread($this->rSocket, 2));
        $aDetails = array();

        for($iIndex = 0; $iIndex < $iPlayerCount; ++$iIndex)
        {
            $aPlayer['playerid'] = (integer) ord(fread($this->rSocket, 1));

            $iStrlen = ord(fread($this->rSocket, 1));
            $aPlayer['nickname'] = (string) fread($this->rSocket, $iStrlen);

            $aPlayer['score'] = (integer) $this->toInteger(fread($this->rSocket, 4));
            $aPlayer['ping'] = (integer) $this->toInteger(fread($this->rSocket, 4));

            $aDetails[] = $aPlayer;
            unset($aPlayer);
        }

        return $aDetails;
    }


    /**
     *	This function gets all the server rules from the server.
     *
     *	Rules in this context are not player rules, they are client rules,
     *	like the weather of the server, time, and so on. (Custom rules,
     *	when supported by a SA-MP plugin, will be included here.)
     *
     *	<code>
     *	Array
     *	(
     *		[gravity] => 0.007900
     *		[mapname] => Everystuff
     *		[version] => 0.3a
     *		[weather] => 0
     *		[weburl] => samp.madoshi.net
     *		[worldtime] => 12:00
     *	)
     *	</code>
     *
     *	@return array Array of server rules.
     */
    public function getRules()
    {
        @fwrite($this->rSocket, $this->createPacket('r'));
        fread($this->rSocket, 11);

        $iRuleCount = ord(fread($this->rSocket, 2));
        $aReturn = array();

        for($iIndex = 0; $iIndex < $iRuleCount; ++$iIndex)
        {
            $iStrlen = ord(fread($this->rSocket, 1));
            $sRulename = (string) fread($this->rSocket, $iStrlen);

            $iStrlen = ord(fread($this->rSocket, 1));
            $aDetails[$sRulename] = (string) fread($this->rSocket, $iStrlen);
        }

        return $aDetails;
    }


    /**
     *	@ignore
     */
    private function toInteger($sData)
    {
        if($sData === "")
        {
            return null;
        }

        $iInteger = 0;
        $iInteger += (ord($sData[0]));

        if(isset($sData[1]))
        {
            $iInteger += (ord($sData[1]) << 8);
        }

        if(isset($sData[2]))
        {
            $iInteger += (ord($sData[2]) << 16);
        }

        if(isset($sData[3]))
        {
            $iInteger += (ord($sData[3]) << 24);
        }

        if($iInteger >= 4294967294)
        {
            $iInteger -= 4294967296;
        }

        return $iInteger;
    }


    /**
     *	@ignore
     */
    private function createPacket($sPayload)
    {
        $sPacket = 'SAMP';
        $sPacket .= chr(strtok($this->aServer[0], '.'));
        $sPacket .= chr(strtok('.'));
        $sPacket .= chr(strtok('.'));
        $sPacket .= chr(strtok('.'));
        $sPacket .= chr($this->aServer[1] & 0xFF);
        $sPacket .= chr($this->aServer[1] >> 8 & 0xFF);
        $sPacket .= $sPayload;

        return $sPacket;
    }

    // Returns the current player count
    // Results are stored in cache for 30 seconds to reduce load
    public static function getServerPlayerCount(bool $forceLiveCheck = false): int
    {
        return Cache::remember('current_player_count', 30, function() {
            $server = new SampQueryAPI(config('app.server_ip'), config('app.server_ip_port'));
            if ($server->isOnline()) {
                return $server->getInfo()['players'];
            }
            return -1;
        });
    }
}
