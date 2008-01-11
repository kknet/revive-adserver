<?php

/*
+---------------------------------------------------------------------------+
| Openads v2.3                                                              |
| ============                                                              |
|                                                                           |
| Copyright (c) 2003-2007 Openads Limited                                   |
| For contact details, see: http://www.openads.org/                         |
|                                                                           |
| This program is free software; you can redistribute it and/or modify      |
| it under the terms of the GNU General Public License as published by      |
| the Free Software Foundation; either version 2 of the License, or         |
| (at your option) any later version.                                       |
|                                                                           |
| This program is distributed in the hope that it will be useful,           |
| but WITHOUT ANY WARRANTY; without even the implied warranty of            |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
| GNU General Public License for more details.                              |
|                                                                           |
| You should have received a copy of the GNU General Public License         |
| along with this program; if not, write to the Free Software               |
| Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA |
+---------------------------------------------------------------------------+
$Id $
*/

require_once MAX_PATH . '/etc/changes/migration_tables_core_546.php';
require_once MAX_PATH . '/lib/OA/DB/Sql.php';
require_once MAX_PATH . '/etc/changes/tests/unit/MigrationTest.php';
require_once MAX_PATH . '/lib/OA/Upgrade/Upgrade.php';

/**
 * Test for migration class #546
 *
 *
 * @package    changes
 * @subpackage TestSuite
 * @author     Matteo Beccati <matteo.beccati@openads.org>
 */
class Migration_546Test extends MigrationTest
{
    var $tblPrefsOld;
    var $tblAgency;
    var $tblAffilates;
    var $tblChannel;
    var $tblClients;
    var $tblUsers;
    var $tblAccounts;
    var $tblPrefsNew;
    var $tblAccPrefs;

    var $aPrefsOld = array(
                            'config_version' => 200.314,
                            'my_header' => 'this is MY header',
                            'my_footer' => 'this is MY footer',
                            'my_logo' => 'this is MY logo',
                            'language' => 'australian',
                            'name' => 'AdServer',
                            'company_name' => 'adhd.com',
                            'override_gd_imageformat' => '',
                            'begin_of_week' => 1,
                            'percentage_decimals' => 2,
                            'type_sql_allow' => 't',
                            'type_url_allow' => 't',
                            'type_web_allow' => 't',
                            'type_html_allow' => 't',
                            'type_txt_allow' => 't',
                            'banner_html_auto' => 't',
                            'warn_admin' => 't',
                            'warn_agency' => 't',
                            'warn_client' => 't',
                            'warn_limit' => 2000,
                            'admin_email_headers' => '',
                            'admin_novice' => 't',
                            'default_banner_weight' => 1,
                            'default_campaign_weight' => 1,
                            'default_banner_url' => '',
                            'default_banner_destination' => '',
                            'client_welcome' => 'Oi!!!',
                            'client_welcome_msg' => 'hi there client!',
                            'publisher_welcome' => 'OiOi!!!',
                            'publisher_welcome_msg' => 'hi there publisher!',
                            'content_gzip_compression' => 't',
                            'userlog_email' => 'userlog@example.com',
                            'gui_show_campaign_info' => 't',
                            'gui_show_campaign_preview' => 't',
                            'gui_campaign_anonymous' => '',
                            'gui_show_banner_info' => 't',
                            'gui_show_banner_preview' => 't',
                            'gui_show_banner_html' => 't',
                            'gui_show_matching' => 't',
                            'gui_show_parents' => 't',
                            'gui_hide_inactive' => '',
                            'gui_link_compact_limit' => 50,
                            'gui_header_background_color' => '#FFFFFF',
                            'gui_header_foreground_color' => '#000000',
                            'gui_header_active_tab_color' => '#111111',
                            'gui_header_text_color' => '#777777',
                            'gui_invocation_3rdparty_default' => '0',
                            'qmail_patch' => '',
                            'updates_enabled' => 't',
                            'updates_cache' => 'b:0;',
                            'updates_timestamp' => 1199316921,
                            'updates_last_seen' => 0.000,
                            'allow_invocation_plain' => 't',
                            'allow_invocation_plain_nocookies' => '',
                            'allow_invocation_js' => 't',
                            'allow_invocation_frame' => '',
                            'allow_invocation_xmlrpc' => '',
                            'allow_invocation_local' => '',
                            'allow_invocation_interstitial' => '',
                            'allow_invocation_popup' => '',
                            'allow_invocation_clickonly' => 't',
                            'auto_clean_tables' => 'f',
                            'auto_clean_tables_interval' => 5,
                            'auto_clean_userlog' => 't',
                            'auto_clean_userlog_interval' => 3,
                            'auto_clean_tables_vacuum' => 't',
                            'autotarget_factor' => 0.69,
                            'maintenance_timestamp' => 1199354707,
                            'compact_stats' => 't',
                            'statslastday' => '0000-00-00',
                            'statslasthour' => 0,
                            'default_tracker_status' => 1,
                            'default_tracker_type' => 1,
                            'default_tracker_linkcampaigns' => 'f',
                            'publisher_agreement' => '',
                            'publisher_agreement_text' => '',
                            'publisher_payment_modes' => '',
                            'publisher_currencies' => '',
                            'publisher_categories' => '',
                            'publisher_help_files' => '',
                            'publisher_default_tax_id' => '',
                            'publisher_default_approved' => '',
                            'more_reports' => null,
                            'gui_column_id'                     => array('show'=>0,'label'=>'id','rank'=>1),
                            'gui_column_requests'               => array('show'=>1,'label'=>'requests','rank'=>2),
                            'gui_column_impressions'            => array('show'=>0,'label'=>'impressions','rank'=>3),
                            'gui_column_clicks'                 => array('show'=>1,'label'=>'clicks','rank'=>4),
                            'gui_column_ctr'                    => array('show'=>1,'label'=>'ctr','rank'=>5),
                            'gui_column_conversions'            => array('show'=>1,'label'=>'conversions','rank'=>6),
                            'gui_column_conversions_pending'    => array('show'=>0,'label'=>'conversions_pending','rank'=>7),
                            'gui_column_sr_views'               => array('show'=>1,'label'=>'sr_views','rank'=>8),
                            'gui_column_sr_clicks'              => array('show'=>0,'label'=>'sr_clicks','rank'=>9),
                            'gui_column_revenue'                => array('show'=>1,'label'=>'revenue','rank'=>10),
                            'gui_column_cost'                   => array('show'=>1,'label'=>'cost','rank'=>11),
                            'gui_column_bv'                     => array('show'=>0,'label'=>'bv','rank'=>12),
                            'gui_column_num_items'              => array('show'=>0,'label'=>'num_items','rank'=>13),
                            'gui_column_revcpc'                 => array('show'=>1,'label'=>'revcpc','rank'=>14),
                            'gui_column_costcpc'                => array('show'=>1,'label'=>'costcpc','rank'=>15),
                            'gui_column_technology_cost'        => array('show'=>1,'label'=>'technology_cost','rank'=>16),
                            'gui_column_income'                 => array('show'=>1,'label'=>'income','rank'=>17),
                            'gui_column_income_margin'          => array('show'=>1,'label'=>'income_margin','rank'=>18),
                            'gui_column_profit'                 => array('show'=>1,'label'=>'profit','rank'=>19),
                            'gui_column_margin'                 => array('show'=>1,'label'=>'margin','rank'=>20),
                            'gui_column_erpm'                   => array('show'=>1,'label'=>'erpm','rank'=>21),
                            'gui_column_erpc'                   => array('show'=>1,'label'=>'erpc','rank'=>22),
                            'gui_column_erps'                   => array('show'=>1,'label'=>'erps','rank'=>23),
                            'gui_column_eipm'                   => array('show'=>1,'label'=>'eipm','rank'=>24),
                            'gui_column_eipc'                   => array('show'=>1,'label'=>'eipc','rank'=>25),
                            'gui_column_eips'                   => array('show'=>1,'label'=>'eips','rank'=>26),
                            'gui_column_ecpm'                   => array('show'=>1,'label'=>'ecpm','rank'=>27),
                            'gui_column_ecpc'                   => array('show'=>1,'label'=>'ecpc','rank'=>28),
                            'gui_column_ecps'                   => array('show'=>1,'label'=>'ecps','rank'=>29),
                            'gui_column_epps'                   => array('show'=>0,'label'=>'epps','rank'=>30),
                            'maintenance_cron_timestamp' => 1198674011,
                            'warn_limit_days' => 1);

    function Migration_546Test()
    {
        $this->oDbh = &OA_DB::singleton();
        $prefix = $this->getPrefix();
        $this->tblPrefsOld  = $this->oDbh->quoteIdentifier($prefix.'preference', true);
        $this->tblAgency    = $this->oDbh->quoteIdentifier($prefix.'agency', true);
        $this->tblAffilates = $this->oDbh->quoteIdentifier($prefix.'affiliates', true);
        $this->tblChannel   = $this->oDbh->quoteIdentifier($prefix.'channel', true);
        $this->tblClients   = $this->oDbh->quoteIdentifier($prefix.'clients', true);
        $this->tblUsers     = $this->oDbh->quoteIdentifier($prefix.'users', true);

        $this->tblAccounts  = $this->oDbh->quoteIdentifier($prefix.'accounts', true);
        $this->tblPrefsNew  = $this->oDbh->quoteIdentifier($prefix.'preferences',true);
        $this->tblAccPrefs  = $this->oDbh->quoteIdentifier($prefix.'account_preference_assoc',true);

        $this->_setupAccounts();
        $this->_setupPreferences();
        $this->_setupSettings();
    }

    function setUp()
    {
    }

    function tearDown()
    {
    }

    function _setupAccounts()
    {

        $this->initDatabase(542, array('agency', 'affiliates', 'application_variable', 'audit', 'channel', 'clients', 'preference'));


        $this->oDbh->exec("INSERT INTO {$this->tblPrefsOld} (agencyid, admin_fullname, admin_email, admin, admin_pw) VALUES
            (0, 'Administrator', 'admin@example.com', 'admin', 'admin')");
        $this->oDbh->exec("INSERT INTO {$this->tblPrefsOld} (agencyid) VALUES (1)");

        $this->oDbh->exec("INSERT INTO {$this->tblAgency} (name, email) VALUES ('Agency 1', 'ag1@example.com')");
        $this->oDbh->exec("INSERT INTO {$this->tblAgency} (name, email, username, password) VALUES ('Agency 2', 'ag2@example.com', 'agency2', 'agency2')");
        $this->oDbh->exec("INSERT INTO {$this->tblAgency} (name, email, username, password) VALUES ('Agency 3', 'ag3@example.com', 'agency3', 'agency3')");
        $this->oDbh->exec("INSERT INTO {$this->tblAgency} (name, email, username, password) VALUES ('Agency 4', 'ag4@example.com', 'agency4', NULL)");
        $this->oDbh->exec("INSERT INTO {$this->tblAgency} (name, email, username, password) VALUES ('Agency 5', 'ag5@example.com', NULL, 'agency3')");

        $this->oDbh->exec("INSERT INTO {$this->tblAffilates} (name, email, agencyid) VALUES ('Publisher 1', 'pu1@example.com', 1)");
        $this->oDbh->exec("INSERT INTO {$this->tblAffilates} (name, email, username, password) VALUES ('Publisher 2', 'pu2@example.com', 'publisher2', 'publisher2')");

        $this->oDbh->exec("INSERT INTO {$this->tblChannel} (name, agencyid, affiliateid) VALUES ('Channel 1', 0, 0)");
        $this->oDbh->exec("INSERT INTO {$this->tblChannel} (name, agencyid, affiliateid) VALUES ('Channel 2', 0, 2)");
        $this->oDbh->exec("INSERT INTO {$this->tblChannel} (name, agencyid, affiliateid) VALUES ('Channel 3', 1, 0)");
        $this->oDbh->exec("INSERT INTO {$this->tblChannel} (name, agencyid, affiliateid) VALUES ('Channel 4', 1, 1)");

        $this->oDbh->exec("INSERT INTO {$this->tblClients} (clientname, email, agencyid) VALUES ('Advertiser 1', 'ad1@example.com', 1)");
        $this->oDbh->exec("INSERT INTO {$this->tblClients} (clientname, email, clientusername, clientpassword) VALUES ('Advertiser 2', 'ad2@example.com', 'advertiser2', 'advertiser2')");

    }

    function _setupPreferences()
    {
        $query = "UPDATE {$this->tblPrefsOld} SET ";

        $n = count($this->aPrefsOld)-1;
        $i = 0;
        foreach ($this->aPrefsOld AS $k => $v)
        {
            if (is_array($v))
            {
                $v = serialize($v);
            }
            $query.= $k."=".$v = $this->oDbh->quote($v, null, true, false);
            if ($i < $n)
            {
                $query.= ",";
            }
            $i++;
        }
        $query.= " WHERE agencyid = 0";
        $this->oDbh->exec($query);
    }

    function _setupSettings()
    {
        $aConf = &$GLOBALS['_MAX']['CONF'];
        $aSettingsExpectations = $this->_getSettingsExpectations();
        foreach ($aSettingsExpectations AS $section => $aPair)
        {
            $name = key($aPair);
            unset($aConf[$section][$name]);
        }
    }

    function testUpgradeSchema()
    {
        $this->upgradeToVersion(543);
        $this->upgradeToVersion(544);
        $this->upgradeToVersion(546);
    }

    function testMigratePrefsToSettings()
    {
        $aConf = $GLOBALS['_MAX']['CONF'];
        $aSettingsExpectations = $this->_getSettingsExpectations();
        foreach ($aSettingsExpectations AS $section => $aPair)
        {
            $name = key($aPair);
            $value = $aPair[$name];
            //$value = $oMig->aPrefNew[$section][$aPair[$name]];
            $this->assertTrue(isset($aConf[$section]),'section missing');
            $this->assertTrue(isset($aConf[$section][$name]),'key missing');
            $this->assertEqual($aConf[$section][$name],$value,'incorrect value');
        }
        TestEnv::restoreConfig();
    }

    function testMigratePrefsToPrefs()
    {
        $query = "SELECT p.preference_name AS name, ap.value AS value
                    FROM {$this->tblAccPrefs} AS ap
                    LEFT JOIN {$this->tblPrefsNew} AS p ON p.preference_id = ap.preference_id
                    LEFT JOIN {$this->tblAccounts} AS a ON a.account_id = ap.account_id
                    WHERE a.account_type = 'ADMIN'"
                 ;
        $aResults       = $this->oDbh->queryAll($query,null, null, true);
        $aExpectations  =  $this->_getPrefsExpectations();
        $this->assertEqual(count($aResults),count($aExpectations));
        foreach ($aResults as $nameNew => $valNew)
        {
            $this->assertTrue(array_key_exists($nameNew,$aExpectations));
            if (array_key_exists($nameNew,$aExpectations))
            {
                  $this->assertEqual($valNew,$aExpectations[$nameNew],'wrong value for '.$nameNew);
            }
        }
    }

    function testMigrateUsers()
    {
        $aAgencies   = $this->oDbh->queryAll("SELECT agencyid, name, email, account_id FROM {$this->tblAgency} ORDER BY agencyid");
        $aAffiliates = $this->oDbh->queryAll("SELECT affiliateid, agencyid, account_id FROM {$this->tblAffilates} ORDER BY affiliateid");
        $aChannels   = $this->oDbh->queryAll("SELECT channelid, agencyid FROM {$this->tblChannel} ORDER BY channelid");
        $aClients    = $this->oDbh->queryAll("SELECT clientid, agencyid, account_id FROM {$this->tblClients} ORDER BY clientid");
        $aAccounts   = $this->oDbh->queryAll("SELECT * FROM {$this->tblAccounts} ORDER BY account_id");
        $aUsers      = $this->oDbh->queryAll("SELECT * FROM {$this->tblUsers} ORDER BY user_id");

        // Check Admin
        $acCount = 2;
        $usCount = 1;
        $aReturnAgencies = array_slice($aAgencies, -1);
        $aReturnAccounts = array_slice($aAccounts, 0, $acCount);
        $aReturnUsers    = array_slice($aUsers, 0, $usCount);

        $this->assertEqual($aReturnAgencies, $this->_getAdminAgencies());
        $this->assertEqual($aReturnAccounts, $this->_getAdminAccounts());
        $this->assertEqual($aReturnUsers,    $this->_getAdminUsers());

        // Check Manager
        $ac = 5;
        $us = 2;
        $acOffset = $acCount;
        $acCount  += $ac;
        $usOffset = $usCount;
        $usCount  += $us;
        $aReturnAgencies = array_slice($aAgencies, 0, $ac);
        $aReturnAccounts = array_slice($aAccounts, $acOffset, $ac);
        $aReturnUsers    = array_slice($aUsers, $usOffset, $us);

        $this->assertEqual($aReturnAgencies, $this->_getManagerAgencies());
        $this->assertEqual($aReturnAccounts, $this->_getManagerAccounts());
        $this->assertEqual($aReturnUsers,    $this->_getManagerUsers());

        // Check Advertiser
        $ac = 2;
        $us = 1;
        $acOffset = $acCount;
        $acCount  += $ac;
        $usOffset = $usCount;
        $usCount  += $us;
        $aReturnClients  = array_slice($aClients, 0, $ac);
        $aReturnAccounts = array_slice($aAccounts, $acOffset, $ac);
        $aReturnUsers    = array_slice($aUsers, $usOffset, $us);

        $this->assertEqual($aReturnClients,  $this->_getAdvertiserClients());
        $this->assertEqual($aReturnAccounts, $this->_getAdvertiserAccounts());
        $this->assertEqual($aReturnUsers,    $this->_getAdvertiserUsers());

        // Check Trafficker
        $ac = 2;
        $us = 1;
        $acOffset = $acCount;
        $acCount  += $ac;
        $usOffset = $usCount;
        $usCount  += $us;
        $aReturnAffiliates = array_slice($aAffiliates, 0, $ac);
        $aReturnAccounts   = array_slice($aAccounts, $acOffset, $ac);
        $aReturnUsers      = array_slice($aUsers, $usOffset, $us);

        $this->assertEqual($aReturnAffiliates, $this->_getTraffickerAffiliates());
        $this->assertEqual($aReturnAccounts,   $this->_getTraffickerAccounts());
        $this->assertEqual($aReturnUsers,      $this->_getTraffickerUsers());

        // Check channels
        $this->assertEqual($aChannels, $this->_getChannels());
   }

   function _getAdminAgencies()
   {
       return array (
          0 =>
          array (
            'agencyid' => '6',
            'name' => 'Default manager',
            'email' => 'admin@example.com',
            'account_id' => '2',
          ),
        );
   }

   function _getAdminAccounts()
   {
       return array (
          0 =>
          array (
            'account_id' => '1',
            'account_type' => 'ADMIN',
            'account_name' => 'Administrator',
          ),
          1 =>
          array (
            'account_id' => '2',
            'account_type' => 'MANAGER',
            'account_name' => 'Default manager',
          ),
        );
   }

   function _getAdminUsers()
   {
       return array (
          0 =>
          array (
            'user_id' => '1',
            'contact_name' => 'Administrator',
            'email_address' => 'admin@example.com',
            'username' => 'admin',
            'password' => 'admin',
            'default_account_id' => '2',
            'comments' => NULL,
            'active'   => '1',
          ),
        );
   }

   function _getManagerAgencies()
   {
       return array (
          0 =>
          array (
            'agencyid' => '1',
            'name' => 'Agency 1',
            'email' => 'ag1@example.com',
            'account_id' => '3',
          ),
          1 =>
          array (
            'agencyid' => '2',
            'name' => 'Agency 2',
            'email' => 'ag2@example.com',
            'account_id' => '4',
          ),
          2 =>
          array (
            'agencyid' => '3',
            'name' => 'Agency 3',
            'email' => 'ag3@example.com',
            'account_id' => '5',
          ),
          3 =>
          array (
            'agencyid' => '4',
            'name' => 'Agency 4',
            'email' => 'ag4@example.com',
            'account_id' => '6',
          ),
          4 =>
          array (
            'agencyid' => '5',
            'name' => 'Agency 5',
            'email' => 'ag5@example.com',
            'account_id' => '7',
          ),
        );
   }

   function _getManagerAccounts()
   {
       return array (
          0 =>
          array (
            'account_id' => '3',
            'account_type' => 'MANAGER',
            'account_name' => 'Agency 1',
          ),
          1 =>
          array (
            'account_id' => '4',
            'account_type' => 'MANAGER',
            'account_name' => 'Agency 2',
          ),
          2 =>
          array (
            'account_id' => '5',
            'account_type' => 'MANAGER',
            'account_name' => 'Agency 3',
          ),
          3 =>
          array (
            'account_id' => '6',
            'account_type' => 'MANAGER',
            'account_name' => 'Agency 4',
          ),
          4 =>
          array (
            'account_id' => '7',
            'account_type' => 'MANAGER',
            'account_name' => 'Agency 5',
          ),
        );
   }

   function _getManagerUsers()
   {
       return array (
          0 =>
          array (
            'user_id' => '2',
            'contact_name' => 'Agency 2',
            'email_address' => 'ag2@example.com',
            'username' => 'agency2',
            'password' => 'agency2',
            'default_account_id' => '4',
            'comments' => NULL,
            'active'   => '1',
          ),
          1 =>
          array (
            'user_id' => '3',
            'contact_name' => 'Agency 3',
            'email_address' => 'ag3@example.com',
            'username' => 'agency3',
            'password' => 'agency3',
            'default_account_id' => '5',
            'comments' => NULL,
            'active'   => '1',
          ),
        );
   }

   function _getTraffickerAffiliates()
   {
       return array (
          0 =>
          array (
            'affiliateid' => '1',
            'agencyid' => '1',
            'account_id' => '10',
          ),
          1 =>
          array (
            'affiliateid' => '2',
            'agencyid' => '6',
            'account_id' => '11',
          ),
        );
   }

   function _getTraffickerAccounts()
   {
       return array (
          0 =>
          array (
            'account_id' => '10',
            'account_type' => 'TRAFFICKER',
            'account_name' => 'Publisher 1',
          ),
          1 =>
          array (
            'account_id' => '11',
            'account_type' => 'TRAFFICKER',
            'account_name' => 'Publisher 2',
          ),
        );
   }

   function _getTraffickerUsers()
   {
       return array (
          0 =>
          array (
            'user_id' => '5',
            'contact_name' => 'Publisher 2',
            'email_address' => 'pu2@example.com',
            'username' => 'publisher2',
            'password' => 'publisher2',
            'default_account_id' => '11',
            'comments' => NULL,
            'active'   => '1',
          ),
        );
   }

   function _getAdvertiserClients()
   {
       return array (
          0 =>
          array (
            'clientid' => '1',
            'agencyid' => '1',
            'account_id' => '8',
          ),
          1 =>
          array (
            'clientid' => '2',
            'agencyid' => '6',
            'account_id' => '9',
          ),
        );
   }

   function _getAdvertiserAccounts()
   {
       return array (
          0 =>
          array (
            'account_id' => '8',
            'account_type' => 'ADVERTISER',
            'account_name' => 'Advertiser 1',
          ),
          1 =>
          array (
            'account_id' => '9',
            'account_type' => 'ADVERTISER',
            'account_name' => 'Advertiser 2',
          ),
        );
   }

   function _getAdvertiserUsers()
   {
       return array (
          0 =>
          array (
            'user_id' => '4',
            'contact_name' => 'Advertiser 2',
            'email_address' => 'ad2@example.com',
            'username' => 'advertiser2',
            'password' => 'advertiser2',
            'default_account_id' => '9',
            'comments' => NULL,
            'active'   => '1',
          ),
        );
   }

   function _getChannels()
   {
       return array (
          0 =>
          array (
            'channelid' => '1',
            'agencyid' => '6',
          ),
          1 =>
          array (
            'channelid' => '2',
            'agencyid' => '6',
          ),
          2 =>
          array (
            'channelid' => '3',
            'agencyid' => '1',
          ),
          3 =>
          array (
            'channelid' => '4',
            'agencyid' => '1',
          ),
        );
   }

   function _getSettingsExpectations()
   {
        $oMig = & new Migration_546();
        foreach ($oMig->aConfMap AS $section => $aPair)
        {
            $name = key($aPair);
            $value = $this->aPrefsOld[$aPair[$name]];
            $aResult[$section][$name] = $value;
        }
        return $aResult;
   }

   function _getPrefsExpectations()
   {
    return array(
                'language'=> $this->aPrefsOld['language'],
                'ui_week_start_day'=> $this->aPrefsOld['begin_of_week'],
                'ui_percentage_decimals'=> $this->aPrefsOld['percentage_decimals'],
                'warn_admin'=> $this->aPrefsOld['warn_admin'],
                'warn_email_manager'=> $this->aPrefsOld['warn_agency'],
                'warn_email_advertiser'=> $this->aPrefsOld['warn_client'],
                'warn_email_admin_impression_limit'=> $this->aPrefsOld['warn_limit'],
                'ui_novice_user'=> $this->aPrefsOld['admin_novice'],
                'default_banner_weight'=> $this->aPrefsOld['default_banner_weight'],
                'default_campaign_weight'=> $this->aPrefsOld['default_campaign_weight'],
                'default_banner_image_url'=> $this->aPrefsOld['default_banner_url'],
                'default_banner_destination_url'=> $this->aPrefsOld['default_banner_destination'],
                'ui_show_campaign_info'=> $this->aPrefsOld['gui_show_campaign_info'],
                'ui_show_campaign_preview'=> $this->aPrefsOld['gui_show_campaign_preview'],
                'ui_show_banner_info'=> $this->aPrefsOld['gui_show_banner_info'],
                'ui_show_banner_preview'=> $this->aPrefsOld['gui_show_banner_preview'],
                'ui_show_banner_html'=> $this->aPrefsOld['gui_show_banner_html'],
                'ui_show_matching_banners'=> $this->aPrefsOld['gui_show_matching'],
                'ui_show_matching_banners_parents'=> $this->aPrefsOld['gui_show_parents'],
                'ui_hide_inactive'=> $this->aPrefsOld['gui_hide_inactive'],
                'tracker_default_status'=> $this->aPrefsOld['default_tracker_status'],
                'tracker_default_type'=> $this->aPrefsOld['default_tracker_type'],
                'tracker_link_campaigns'=> $this->aPrefsOld['default_tracker_linkcampaigns'],
                'ui_column_id' => $this->aPrefsOld['gui_column_id']['show'],
                'ui_column_requests' => $this->aPrefsOld['gui_column_requests']['show'],
                'ui_column_impressions' => $this->aPrefsOld['gui_column_impressions']['show'],
                'ui_column_clicks' => $this->aPrefsOld['gui_column_clicks']['show'],
                'ui_column_ctr' => $this->aPrefsOld['gui_column_ctr']['show'],
                'ui_column_conversions' => $this->aPrefsOld['gui_column_conversions']['show'],
                'ui_column_conversions_pending' => $this->aPrefsOld['gui_column_conversions_pending']['show'],
                'ui_column_sr_views' => $this->aPrefsOld['gui_column_sr_views']['show'],
                'ui_column_sr_clicks' => $this->aPrefsOld['gui_column_sr_clicks']['show'],
                'ui_column_revenue' => $this->aPrefsOld['gui_column_revenue']['show'],
                'ui_column_cost' => $this->aPrefsOld['gui_column_cost']['show'],
                'ui_column_bv' => $this->aPrefsOld['gui_column_bv']['show'],
                'ui_column_num_items' => $this->aPrefsOld['gui_column_num_items']['show'],
                'ui_column_revcpc' => $this->aPrefsOld['gui_column_revcpc']['show'],
                'ui_column_costcpc' => $this->aPrefsOld['gui_column_costcpc']['show'],
                'ui_column_technology_cost' => $this->aPrefsOld['gui_column_technology_cost']['show'],
                'ui_column_income' => $this->aPrefsOld['gui_column_income']['show'],
                'ui_column_income_margin' => $this->aPrefsOld['gui_column_income_margin']['show'],
                'ui_column_profit' => $this->aPrefsOld['gui_column_profit']['show'],
                'ui_column_margin' => $this->aPrefsOld['gui_column_margin']['show'],
                'ui_column_erpm' => $this->aPrefsOld['gui_column_erpm']['show'],
                'ui_column_erpc' => $this->aPrefsOld['gui_column_erpc']['show'],
                'ui_column_erps' => $this->aPrefsOld['gui_column_erps']['show'],
                'ui_column_eipm' => $this->aPrefsOld['gui_column_eipm']['show'],
                'ui_column_eipc' => $this->aPrefsOld['gui_column_eipc']['show'],
                'ui_column_eips' => $this->aPrefsOld['gui_column_eips']['show'],
                'ui_column_ecpm' => $this->aPrefsOld['gui_column_ecpm']['show'],
                'ui_column_ecpc' => $this->aPrefsOld['gui_column_ecpc']['show'],
                'ui_column_ecps' => $this->aPrefsOld['gui_column_ecps']['show'],
                'ui_column_epps' => $this->aPrefsOld['gui_column_epps']['show'],
                'ui_column_id_label' => $this->aPrefsOld['gui_column_id']['label'],
                'ui_column_requests_label' => $this->aPrefsOld['gui_column_requests']['label'],
                'ui_column_impressions_label' => $this->aPrefsOld['gui_column_impressions']['label'],
                'ui_column_clicks_label' => $this->aPrefsOld['gui_column_clicks']['label'],
                'ui_column_ctr_label' => $this->aPrefsOld['gui_column_ctr']['label'],
                'ui_column_conversions_label' => $this->aPrefsOld['gui_column_conversions']['label'],
                'ui_column_conversions_pending_label' => $this->aPrefsOld['gui_column_conversions_pending']['label'],
                'ui_column_sr_views_label' => $this->aPrefsOld['gui_column_sr_views']['label'],
                'ui_column_sr_clicks_label' => $this->aPrefsOld['gui_column_sr_clicks']['label'],
                'ui_column_revenue_label' => $this->aPrefsOld['gui_column_revenue']['label'],
                'ui_column_cost_label' => $this->aPrefsOld['gui_column_cost']['label'],
                'ui_column_bv_label' => $this->aPrefsOld['gui_column_bv']['label'],
                'ui_column_num_items_label' => $this->aPrefsOld['gui_column_num_items']['label'],
                'ui_column_revcpc_label' => $this->aPrefsOld['gui_column_revcpc']['label'],
                'ui_column_costcpc_label' => $this->aPrefsOld['gui_column_costcpc']['label'],
                'ui_column_technology_cost_label' => $this->aPrefsOld['gui_column_technology_cost']['label'],
                'ui_column_income_label' => $this->aPrefsOld['gui_column_income']['label'],
                'ui_column_income_margin_label' => $this->aPrefsOld['gui_column_income_margin']['label'],
                'ui_column_profit_label' => $this->aPrefsOld['gui_column_profit']['label'],
                'ui_column_margin_label' => $this->aPrefsOld['gui_column_margin']['label'],
                'ui_column_erpm_label' => $this->aPrefsOld['gui_column_erpm']['label'],
                'ui_column_erpc_label' => $this->aPrefsOld['gui_column_erpc']['label'],
                'ui_column_erps_label' => $this->aPrefsOld['gui_column_erps']['label'],
                'ui_column_eipm_label' => $this->aPrefsOld['gui_column_eipm']['label'],
                'ui_column_eipc_label' => $this->aPrefsOld['gui_column_eipc']['label'],
                'ui_column_eips_label' => $this->aPrefsOld['gui_column_eips']['label'],
                'ui_column_ecpm_label' => $this->aPrefsOld['gui_column_ecpm']['label'],
                'ui_column_ecpc_label' => $this->aPrefsOld['gui_column_ecpc']['label'],
                'ui_column_ecps_label' => $this->aPrefsOld['gui_column_ecps']['label'],
                'ui_column_epps_label' => $this->aPrefsOld['gui_column_epps']['label'],
                'ui_column_id_rank' => $this->aPrefsOld['gui_column_id']['rank'],
                'ui_column_requests_rank' => $this->aPrefsOld['gui_column_requests']['rank'],
                'ui_column_impressions_rank' => $this->aPrefsOld['gui_column_impressions']['rank'],
                'ui_column_clicks_rank' => $this->aPrefsOld['gui_column_clicks']['rank'],
                'ui_column_ctr_rank' => $this->aPrefsOld['gui_column_ctr']['rank'],
                'ui_column_conversions_rank' => $this->aPrefsOld['gui_column_conversions']['rank'],
                'ui_column_conversions_pending_rank' => $this->aPrefsOld['gui_column_conversions_pending']['rank'],
                'ui_column_sr_views_rank' => $this->aPrefsOld['gui_column_sr_views']['rank'],
                'ui_column_sr_clicks_rank' => $this->aPrefsOld['gui_column_sr_clicks']['rank'],
                'ui_column_revenue_rank' => $this->aPrefsOld['gui_column_revenue']['rank'],
                'ui_column_cost_rank' => $this->aPrefsOld['gui_column_cost']['rank'],
                'ui_column_bv_rank' => $this->aPrefsOld['gui_column_bv']['rank'],
                'ui_column_num_items_rank' => $this->aPrefsOld['gui_column_num_items']['rank'],
                'ui_column_revcpc_rank' => $this->aPrefsOld['gui_column_revcpc']['rank'],
                'ui_column_costcpc_rank' => $this->aPrefsOld['gui_column_costcpc']['rank'],
                'ui_column_technology_cost_rank' => $this->aPrefsOld['gui_column_technology_cost']['rank'],
                'ui_column_income_rank' => $this->aPrefsOld['gui_column_income']['rank'],
                'ui_column_income_margin_rank' => $this->aPrefsOld['gui_column_income_margin']['rank'],
                'ui_column_profit_rank' => $this->aPrefsOld['gui_column_profit']['rank'],
                'ui_column_margin_rank' => $this->aPrefsOld['gui_column_margin']['rank'],
                'ui_column_erpm_rank' => $this->aPrefsOld['gui_column_erpm']['rank'],
                'ui_column_erpc_rank' => $this->aPrefsOld['gui_column_erpc']['rank'],
                'ui_column_erps_rank' => $this->aPrefsOld['gui_column_erps']['rank'],
                'ui_column_eipm_rank' => $this->aPrefsOld['gui_column_eipm']['rank'],
                'ui_column_eipc_rank' => $this->aPrefsOld['gui_column_eipc']['rank'],
                'ui_column_eips_rank' => $this->aPrefsOld['gui_column_eips']['rank'],
                'ui_column_ecpm_rank' => $this->aPrefsOld['gui_column_ecpm']['rank'],
                'ui_column_ecpc_rank' => $this->aPrefsOld['gui_column_ecpc']['rank'],
                'ui_column_ecps_rank' => $this->aPrefsOld['gui_column_ecps']['rank'],
                'ui_column_epps_rank' => $this->aPrefsOld['gui_column_epps']['rank'],
                'warn_email_admin_day_limit'=>$this->aPrefsOld['warn_limit_days']
                );
   }
}