<?php
namespace EAccess\Controller\Component;

use Cake\Controller\Component;
use SoapClient;
use stdClass;

class EAccessComponent extends Component
{

    //To view the parameters necessary for each method of the webservice:
    //https://vsonkenxd105.ent.dfo-mpo.ca:8443/ESASServices/ProfileService?xsd=1

    private $dev_cert_path = "";
    private $prd_cert_path = "";
    private $dev_wsdl = "https://vsonkenxd105.ent.dfo-mpo.ca:8444/ESASServices/ClaimService?wsdl";
    private $prd_wsdl = "https://vsonkenxp127.ent.dfo-mpo.ca:8444/ESASServices/ClaimService?wsdl";

    private $soap_client = null;
    private $passphrase = "";
    private $response = null;
    private $environment = "DEV";

    private $object_id_translations = array(
        'party_id' => 'urn:oid:2.16.124.101.1.260.99.1.1',
        'given_name' => 'urn:oid:2.16.124.101.1.260.99.1.13',
        'family_name' => 'urn:oid:2.16.124.101.1.260.99.1.14',
        'created_on' => 'urn:oid:2.16.124.101.1.260.99.1.23',
        'last_modified_on' => 'urn:oid:2.16.124.101.1.260.99.1.24',
        'effective_from' => 'urn:oid:2.16.124.101.1.260.99.1.25',
        'email' => 'urn:oid:2.16.124.101.1.260.99.1.26',
        'effective_to' => 'urn:oid:2.16.124.101.1.260.99.1.29',
        'party_type' => 'urn:oid:2.16.124.101.1.260.99.1.34',
        'ad_nt_principal' => 'urn:oid:2.16.124.101.1.260.99.2.203',
        'ad_group' => 'urn:oid:2.16.124.101.1.260.99.2.204',
        'ad_display_name' => 'urn:oid:2.16.124.101.1.260.99.2.205',
        'error' => 'urn:oid:2.16.124.101.1.260.99.3.1'
    );

    public function __construct()
    {
        $this->dev_cert_path = "EAccess".DS."aps_cmdb_dev.pem";
        $this->prd_cert_path = "EAccess".DS."aps_cmdb_prd.pem";
        return $this;
    }

    private function get_party_id($result)
    {
        $out = "";
        $out = $this->get_object_id_value($result, "party_id");
        return $out;
    }

    private function get_given_name($result)
    {
        $out = "";
        $out = $this->get_object_id_value($result, "given_name");
        return $out;
    }

    private function get_family_name($result)
    {
        $out = "";
        $out = $this->get_object_id_value($result, "family_name");
        return $out;
    }

    private function get_created_on($result)
    {
        $out = "";
        $out = $this->get_object_id_value($result, "created_on");
        return $out;
    }

    private function get_last_modified_on($result)
    {
        $out = "";
        $out = $this->get_object_id_value($result, "last_modified_on");
        return $out;
    }

    private function get_effective_from($result)
    {
        $out = "";
        $out = $this->get_object_id_value($result, "effective_from");
        return $out;
    }

    private function get_email($result)
    {
        $out = "";
        $out = $this->get_object_id_value($result, "email");
        return $out;
    }

    private function get_effective_to($result)
    {
        $out = "";
        $out = $this->get_object_id_value($result, "effective_to");
        return $out;
    }

    private function get_party_type($result)
    {
        $out = "";
        $out = $this->get_object_id_value($result, "party_type");
        return $out;
    }

    private function get_ad_nt_principal($result)
    {
        $out = "";
        $out = $this->get_object_id_value($result, "ad_nt_principal");
        return $out;
    }

    private function get_ad_group($result)
    {
        $out = array();
        $out = $this->get_object_id_array($result, "ad_group");
        return $out;
    }

    private function get_ad_display_name($result)
    {
        $out = "";
        $out = get_object_id_array($result, "ad_display_name");
        return $out;
    }

    private function get_error($result)
    {

        //multiple results
        if (isset($result->claim)) {
            $result = $result->claim;
        }

        $out = array();
        if (isset($result->objectID) && $result->objectID == $this->object_id_translations['error']) {
            $temp = $result->value;

            $start_bracket = strpos($temp, "[");
            $end_bracket = strpos($temp, "]");

            $out["msg"] = substr($temp, 1, $start_bracket - 2);
            $out["email"] = substr($temp, $start_bracket + 1, $end_bracket - $start_bracket - 1);

            if ($out["msg"] == "no match in Active Directory for email address") {
                $out["code"] = 1;
            } else {
                $out["code"] = 0;
            }
        } else {
            $out["msg"] = "";
            $out["email"] = "";
            $out["code"] = "";
        }
        return $out;
    }


    private function get_cert_path()
    {
        $baseDir = realpath(WETKIT_ROOT) . DS; // Convert relative path to real path (no '..' etc)

        if ($this->get_environment() == "PROD") {
            return $baseDir . $this->prd_cert_path;
        } else {
            return $baseDir . $this->dev_cert_path;
        }
    }

    private function get_passphrase()
    {
        return $this->passphrase;
    }

    private function get_response()
    {
        return $this->response;
    }

    private function get_service_wsdl()
    {
        if ($this->get_environment() == "PROD") {
            return $this->prd_wsdl;
        } else {
            return $this->dev_wsdl;
        }
    }

    private function init($exceptions = false)
    {
        $sc = null;
        $context = stream_context_create(array(
            'ssl' => array(
                'verify_peer' => false,
                'allow_self_signed' => true
            )
        ));
        if ($this->get_cert_path() == "" && !file_exists($this->get_cert_path())) {
            throw new NotFoundException(__('Invalid %s', __('certificate')));
        } else {
            $sc = new SoapClient($this->get_service_wsdl(), array(
                'local_cert' => $this->get_cert_path(),
                'passphrase' => $this->get_passphrase(),
                'exceptions' => false,
                'stream_context' => $context
            ));
            //FIXME add soap client validations!
        }
        $this->set_soap_client($sc);
    }

    private function query_email($var)
    {
        $this->init();

        $getInternalPartyClaims = new stdClass();
        $internalPartyClaimInMessage = new stdClass();

        //get array
        $input = explode(",", $var);

        $internalPartyClaimInMessage->emailAddress = $input;
        $getInternalPartyClaims->internalPartyClaimInMsg = $internalPartyClaimInMessage;

        $response = $this->get_soap_client()->getInternalPartyClaims($getInternalPartyClaims);

        //need to add testing here

        return $response;
    }

    public function is_error($result)
    {
        $out = 0;

        //multiple results
        if (isset($result->claim)) {
            $result = $result->claim;
        }

        if (isset($result->objectID) && $result->objectID == $this->object_id_translations['error']) {
            $out = 1;
        }
        return $out;
    }

    public function is_valid_emails($email_list)
    {
        $out = 1;
        $email_array = explode(",", $email_list);
        if (is_array($email_array)) {
            foreach ($email_array as $e) {
                if (!$this->is_valid_email_address($e)) {
                    $out = 0;
                    break;
                }
            }
        } else {
            $out = 0;
        }
        return $out;
    }

    private function sanitize_query($var)
    {
        $out = "";
        $out = trim($var);
        $out = str_replace(chr(13), "", $out);
        $out = str_replace(chr(10), "", $out);
        $out = str_replace(" ", "", $out);
        return $out;
    }

    private function set_cert_path($var)
    {
        $this->cert_path = $var;
    }

    private function set_environment($var)
    {
        $this->environment = $var;
    }

    private function set_service_wsdl($var)
    {
        $this->wsdl = $var;
    }

    // ***** PRIVATE METHODS *****
    private function get_environment()
    {
        return $this->environment;
    }

    private function get_object_id_value($result, $key)
    {
        $out = "";

        //multiple results
        if (isset($result->claim)) {
            $result = $result->claim;
        }

        foreach ($result as $r) {
            if (isset($r->objectID) && $r->objectID == $this->object_id_translations[$key]) {
                $out = $r->value;
                break;
            }
        }
        return $out;
    }

    private function get_object_id_array($result, $key)
    {
        $out = array();

        //multiple results
        if (isset($result->claim)) {
            $result = $result->claim;
        }

        foreach ($result as $r) {
            if (isset($r->objectID) && $r->objectID == $this->object_id_translations[$key]) {
                array_push($out, $r->value);
            }
        }

        return $out;
    }

    private function get_soap_client()
    {
        return $this->soap_client;
    }

    private function set_response($var)
    {
        $this->response = $var;
    }

    private function set_soap_client($var)
    {
        $this->soap_client = $var;
    }

    private function is_valid_email_address($email, $options = array())
    {

        #
        # you can pass a few different named options as a second argument,
        # but the defaults are usually a good choice.
        #

        $defaults = array(
            'allow_comments' => true,
            'public_internet' => true, # turn this off for 'strict' mode
        );

        $opts = array();
        foreach ($defaults as $k => $v) {
            $opts[$k] = isset($options[$k]) ? $options[$k] : $v;
        }
        $options = $opts;


        ####################################################################################
        #
        # NO-WS-CTL       =       %d1-8 /         ; US-ASCII control characters
        #                         %d11 /          ;  that do not include the
        #                         %d12 /          ;  carriage return, line feed,
        #                         %d14-31 /       ;  and white space characters
        #                         %d127
        # ALPHA          =  %x41-5A / %x61-7A   ; A-Z / a-z
        # DIGIT          =  %x30-39

        $no_ws_ctl = "[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x7f]";
        $alpha = "[\\x41-\\x5a\\x61-\\x7a]";
        $digit = "[\\x30-\\x39]";
        $cr = "\\x0d";
        $lf = "\\x0a";
        $crlf = "(?:$cr$lf)";


        ####################################################################################
        #
        # obs-char        =       %d0-9 / %d11 /          ; %d0-127 except CR and
        #                         %d12 / %d14-127         ;  LF
        # obs-text        =       *LF *CR *(obs-char *LF *CR)
        # text            =       %d1-9 /         ; Characters excluding CR and LF
        #                         %d11 /
        #                         %d12 /
        #                         %d14-127 /
        #                         obs-text
        # obs-qp          =       "\" (%d0-127)
        # quoted-pair     =       ("\" text) / obs-qp

        $obs_char = "[\\x00-\\x09\\x0b\\x0c\\x0e-\\x7f]";
        $obs_text = "(?:$lf*$cr*(?:$obs_char$lf*$cr*)*)";
        $text = "(?:[\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f]|$obs_text)";

        #
        # there's an issue with the definition of 'text', since 'obs_text' can
        # be blank and that allows qp's with no character after the slash. we're
        # treating that as bad, so this just checks we have at least one
        # (non-CRLF) character
        #

        $text = "(?:$lf*$cr*$obs_char$lf*$cr*)";
        $obs_qp = "(?:\\x5c[\\x00-\\x7f])";
        $quoted_pair = "(?:\\x5c$text|$obs_qp)";


        ####################################################################################
        #
        # obs-FWS         =       1*WSP *(CRLF 1*WSP)
        # FWS             =       ([*WSP CRLF] 1*WSP) /   ; Folding white space
        #                         obs-FWS
        # ctext           =       NO-WS-CTL /     ; Non white space controls
        #                         %d33-39 /       ; The rest of the US-ASCII
        #                         %d42-91 /       ;  characters not including "(",
        #                         %d93-126        ;  ")", or "\"
        # ccontent        =       ctext / quoted-pair / comment
        # comment         =       "(" *([FWS] ccontent) [FWS] ")"
        # CFWS            =       *([FWS] comment) (([FWS] comment) / FWS)

        #
        # note: we translate ccontent only partially to avoid an infinite loop
        # instead, we'll recursively strip *nested* comments before processing
        # the input. that will leave 'plain old comments' to be matched during
        # the main parse.
        #

        $wsp = "[\\x20\\x09]";
        $obs_fws = "(?:$wsp+(?:$crlf$wsp+)*)";
        $fws = "(?:(?:(?:$wsp*$crlf)?$wsp+)|$obs_fws)";
        $ctext = "(?:$no_ws_ctl|[\\x21-\\x27\\x2A-\\x5b\\x5d-\\x7e])";
        $ccontent = "(?:$ctext|$quoted_pair)";
        $comment = "(?:\\x28(?:$fws?$ccontent)*$fws?\\x29)";
        $cfws = "(?:(?:$fws?$comment)*(?:$fws?$comment|$fws))";


        #
        # these are the rules for removing *nested* comments. we'll just detect
        # outer comment and replace it with an empty comment, and recurse until
        # we stop.
        #

        $outer_ccontent_dull = "(?:$fws?$ctext|$quoted_pair)";
        $outer_ccontent_nest = "(?:$fws?$comment)";
        $outer_comment = "(?:\\x28$outer_ccontent_dull*(?:$outer_ccontent_nest$outer_ccontent_dull*)+$fws?\\x29)";


        ####################################################################################
        #
        # atext           =       ALPHA / DIGIT / ; Any character except controls,
        #                         "!" / "#" /     ;  SP, and specials.
        #                         "$" / "%" /     ;  Used for atoms
        #                         "&" / "'" /
        #                         "*" / "+" /
        #                         "-" / "/" /
        #                         "=" / "?" /
        #                         "^" / "_" /
        #                         "`" / "{" /
        #                         "|" / "}" /
        #                         "~"
        # atom            =       [CFWS] 1*atext [CFWS]

        $atext = "(?:$alpha|$digit|[\\x21\\x23-\\x27\\x2a\\x2b\\x2d\\x2f\\x3d\\x3f\\x5e\\x5f\\x60\\x7b-\\x7e])";
        $atom = "(?:$cfws?(?:$atext)+$cfws?)";


        ####################################################################################
        #
        # qtext           =       NO-WS-CTL /     ; Non white space controls
        #                         %d33 /          ; The rest of the US-ASCII
        #                         %d35-91 /       ;  characters not including "\"
        #                         %d93-126        ;  or the quote character
        # qcontent        =       qtext / quoted-pair
        # quoted-string   =       [CFWS]
        #                         DQUOTE *([FWS] qcontent) [FWS] DQUOTE
        #                         [CFWS]
        # word            =       atom / quoted-string

        $qtext = "(?:$no_ws_ctl|[\\x21\\x23-\\x5b\\x5d-\\x7e])";
        $qcontent = "(?:$qtext|$quoted_pair)";
        $quoted_string = "(?:$cfws?\\x22(?:$fws?$qcontent)*$fws?\\x22$cfws?)";

        #
        # changed the '*' to a '+' to require that quoted strings are not empty
        #

        $quoted_string = "(?:$cfws?\\x22(?:$fws?$qcontent)+$fws?\\x22$cfws?)";
        $word = "(?:$atom|$quoted_string)";


        ####################################################################################
        #
        # obs-local-part  =       word *("." word)
        # obs-domain      =       atom *("." atom)

        $obs_local_part = "(?:$word(?:\\x2e$word)*)";
        $obs_domain = "(?:$atom(?:\\x2e$atom)*)";


        ####################################################################################
        #
        # dot-atom-text   =       1*atext *("." 1*atext)
        # dot-atom        =       [CFWS] dot-atom-text [CFWS]

        $dot_atom_text = "(?:$atext+(?:\\x2e$atext+)*)";
        $dot_atom = "(?:$cfws?$dot_atom_text$cfws?)";


        ####################################################################################
        #
        # domain-literal  =       [CFWS] "[" *([FWS] dcontent) [FWS] "]" [CFWS]
        # dcontent        =       dtext / quoted-pair
        # dtext           =       NO-WS-CTL /     ; Non white space controls
        #
        #                         %d33-90 /       ; The rest of the US-ASCII
        #                         %d94-126        ;  characters not including "[",
        #                                         ;  "]", or "\"

        $dtext = "(?:$no_ws_ctl|[\\x21-\\x5a\\x5e-\\x7e])";
        $dcontent = "(?:$dtext|$quoted_pair)";
        $domain_literal = "(?:$cfws?\\x5b(?:$fws?$dcontent)*$fws?\\x5d$cfws?)";


        ####################################################################################
        #
        # local-part      =       dot-atom / quoted-string / obs-local-part
        # domain          =       dot-atom / domain-literal / obs-domain
        # addr-spec       =       local-part "@" domain

        $local_part = "(($dot_atom)|($quoted_string)|($obs_local_part))";
        $domain = "(($dot_atom)|($domain_literal)|($obs_domain))";
        $addr_spec = "$local_part\\x40$domain";


        #
        # this was previously 256 based on RFC3696, but dominic's errata was accepted.
        #

        if (strlen($email) > 254) {
            return 0;
        }


        #
        # we need to strip nested comments first - we replace them with a simple comment
        #

        if ($options['allow_comments']) {

            $email = $this->email_strip_comments($outer_comment, $email, "(x)");
        }


        #
        # now match what's left
        #

        if (!preg_match("!^$addr_spec$!", $email, $m)) {

            return 0;
        }

        $bits = array(
            'local' => isset($m[1]) ? $m[1] : '',
            'local-atom' => isset($m[2]) ? $m[2] : '',
            'local-quoted' => isset($m[3]) ? $m[3] : '',
            'local-obs' => isset($m[4]) ? $m[4] : '',
            'domain' => isset($m[5]) ? $m[5] : '',
            'domain-atom' => isset($m[6]) ? $m[6] : '',
            'domain-literal' => isset($m[7]) ? $m[7] : '',
            'domain-obs' => isset($m[8]) ? $m[8] : '',
        );


        #
        # we need to now strip comments from $bits[local] and $bits[domain],
        # since we know they're in the right place and we want them out of the
        # way for checking IPs, label sizes, etc
        #

        if ($options['allow_comments']) {
            $bits['local'] = $this->email_strip_comments($comment, $bits['local']);
            $bits['domain'] = $this->email_strip_comments($comment, $bits['domain']);
        }


        #
        # length limits on segments
        #

        if (strlen($bits['local']) > 64) {
            return 0;
        }
        if (strlen($bits['domain']) > 255) {
            return 0;
        }


        #
        # restrictions on domain-literals from RFC2821 section 4.1.3
        #
        # RFC4291 changed the meaning of :: in IPv6 addresses - i can mean one or
        # more zero groups (updated from 2 or more).
        #

        if (strlen($bits['domain-literal'])) {

            $Snum = "(\d{1,3})";
            $IPv4_address_literal = "$Snum\.$Snum\.$Snum\.$Snum";

            $IPv6_hex = "(?:[0-9a-fA-F]{1,4})";

            $IPv6_full = "IPv6\:$IPv6_hex(?:\:$IPv6_hex){7}";

            $IPv6_comp_part = "(?:$IPv6_hex(?:\:$IPv6_hex){0,7})?";
            $IPv6_comp = "IPv6\:($IPv6_comp_part\:\:$IPv6_comp_part)";

            $IPv6v4_full = "IPv6\:$IPv6_hex(?:\:$IPv6_hex){5}\:$IPv4_address_literal";

            $IPv6v4_comp_part = "$IPv6_hex(?:\:$IPv6_hex){0,5}";
            $IPv6v4_comp = "IPv6\:((?:$IPv6v4_comp_part)?\:\:(?:$IPv6v4_comp_part\:)?)$IPv4_address_literal";


            #
            # IPv4 is simple
            #

            if (preg_match("!^\[$IPv4_address_literal\]$!", $bits['domain'], $m)) {

                if (intval($m[1]) > 255) {
                    return 0;
                }
                if (intval($m[2]) > 255) {
                    return 0;
                }
                if (intval($m[3]) > 255) {
                    return 0;
                }
                if (intval($m[4]) > 255) {
                    return 0;
                }

            } else {

                #
                # this should be IPv6 - a bunch of tests are needed here :)
                #

                while (1) {

                    if (preg_match("!^\[$IPv6_full\]$!", $bits['domain'])) {
                        break;
                    }

                    if (preg_match("!^\[$IPv6_comp\]$!", $bits['domain'], $m)) {
                        list($a, $b) = explode('::', $m[1]);
                        $folded = (strlen($a) && strlen($b)) ? "$a:$b" : "$a$b";
                        $groups = explode(':', $folded);
                        if (count($groups) > 7) {
                            return 0;
                        }
                        break;
                    }

                    if (preg_match("!^\[$IPv6v4_full\]$!", $bits['domain'], $m)) {

                        if (intval($m[1]) > 255) {
                            return 0;
                        }
                        if (intval($m[2]) > 255) {
                            return 0;
                        }
                        if (intval($m[3]) > 255) {
                            return 0;
                        }
                        if (intval($m[4]) > 255) {
                            return 0;
                        }
                        break;
                    }

                    if (preg_match("!^\[$IPv6v4_comp\]$!", $bits['domain'], $m)) {
                        list($a, $b) = explode('::', $m[1]);
                        $b = substr($b, 0, -1); # remove the trailing colon before the IPv4 address
                        $folded = (strlen($a) && strlen($b)) ? "$a:$b" : "$a$b";
                        $groups = explode(':', $folded);
                        if (count($groups) > 5) {
                            return 0;
                        }
                        break;
                    }

                    return 0;
                }
            }
        } else {

            #
            # the domain is either dot-atom or obs-domain - either way, it's
            # made up of simple labels and we split on dots
            #

            $labels = explode('.', $bits['domain']);


            #
            # this is allowed by both dot-atom and obs-domain, but is un-routeable on the
            # public internet, so we'll fail it (e.g. user@localhost)
            #

            if ($options['public_internet']) {
                if (count($labels) == 1) {
                    return 0;
                }
            }


            #
            # checks on each label
            #

            foreach ($labels as $label) {

                if (strlen($label) > 63) {
                    return 0;
                }
                if (substr($label, 0, 1) == '-') {
                    return 0;
                }
                if (substr($label, -1) == '-') {
                    return 0;
                }
            }


            #
            # last label can't be all numeric
            #

            if ($options['public_internet']) {
                if (preg_match('!^[0-9]+$!', array_pop($labels))) {
                    return 0;
                }
            }
        }


        return 1;
    }

    private function email_strip_comments($comment, $email, $replace = '')
    {
        while (1) {
            $new = preg_replace("!$comment!", $replace, $email);
            if (strlen($new) == strlen($email)) {
                return $email;
            }
            $email = $new;
        }
    }

    public function add_users($emails, $env)
    {
        $report_success = array();
        $report_error = array();
        $i = 0;


        $this->set_environment($env);
        $input = $this->sanitize_query($emails);

        //check individual emails
        if (!$this->is_valid_emails($emails)) {
            //$this->Session->setFlash(__('Invalid email in the list.'));
            array_push($report_success, array(
                "index" => $i,
                "success" => 0,
                "msg" => __('Invalid email in the list.'),
                "email" => "",
                "code" => ""
            ));
        } else {
            $error_count = 0;
            $response = $this->query_email($input);

            $this->User = ClassRegistry::init('User');

            //loop each results
            foreach ($response->return->result as $r) {
                $aps_group = "ENT\Infor-Application_Platforms";
                $dev_group = "ENT\XNAT, App-Developers";
                $group_id = 100;

                if (!$this->is_error($r)) {

                    $group_list = $this->get_ad_group($r);

                    //check dev AD group
                    if (in_array($dev_group, $group_list)) {
                        $group_id = 2;
                    }

                    //check aps AD group
                    if (in_array($aps_group, $group_list)) {
                        $group_id = 1;
                    }


                    $data['User']['id'] = $this->get_party_id($r);
                    $data['User']['group_id'] = $group_id;
                    $data['User']['legal_given_names'] = $this->get_given_name($r);
                    $data['User']['legal_family_name'] = $this->get_family_name($r);
                    $data['User']['email'] = strtolower($this->get_email($r));
                    $data['User']['username'] = strtolower($this->get_ad_nt_principal($r));

                    $this->User->save($data);

                    array_push($report_success, array(
                        "index" => $i,
                        "success" => 1,
                        "partyId" => $this->get_party_id($r),
                        "msg" => "",
                        "email" => $data['User']['email'],
                        "code" => ""
                    ));
                } else {
                    $error_count++;
                    $error = $this->EAccess->get_error($r);
                    array_push($report_error, array(
                        "index" => $i,
                        "success" => 0,
                        "msg" => $error['msg'],
                        "email" => $error['email'],
                        "code" => $error['code']
                    ));
                }

                $i++;
            }

            $count = count($response->return->result);

            $out['response'] = $response;
            $out['report_success'] = $report_success;
            $out['report_error'] = $report_error;
            $out['error_count'] = $error_count;

            return $out;
        }
    }


    public function test_users($emails, $env)
    {
        $report_success = array();
        $report_error = array();
        $i = 0;


        $this->set_environment($env);
        $input = $this->sanitize_query($emails);

        if (!$this->is_valid_emails($emails)) {
            //$this->Session->setFlash(__('Invalid email in the list.'));
            array_push($report_success, array(
                "index" => $i,
                "success" => 0,
                "msg" => __('Invalid email in the list.'),
                "email" => "",
                "code" => ""
            ));
        } else {
            $error_count = 0;
            $response = $this->query_email($input);

            //$this->User = ClassRegistry::init('User');
            $this->User = array();

            //loop each results
            foreach ($response->return->result as $r) {
                $aps_group = "ENT\Infor-Application_Platforms";
                $dev_group = "ENT\XNAT, App-Developers";
                $group_id = 100;

                if (!$this->is_error($r)) {

                    $group_list = $this->get_ad_group($r);

                    //check dev AD group
                    if (in_array($dev_group, $group_list)) {
                        $group_id = 2;
                    }

                    //check aps AD group
                    if (in_array($aps_group, $group_list)) {
                        $group_id = 1;
                    }


                    $data['User']['id'] = $this->get_party_id($r);
                    $data['User']['group_id'] = $group_id;
                    $data['User']['legal_given_names'] = $this->get_given_name($r);
                    $data['User']['legal_family_name'] = $this->get_family_name($r);
                    $data['User']['email'] = strtolower($this->get_email($r));
                    $data['User']['username'] = strtolower($this->get_ad_nt_principal($r));

                    //$this->User->save($data);

                    array_push($report_success, array(
                        "index" => $i,
                        "success" => 1,
                        "partyId" => $this->get_party_id($r),
                        "msg" => "",
                        "email" => $data['User']['email'],
                        "code" => ""
                    ));
                } else {
                    $error_count++;
                    $error = $this->EAccess->get_error($r);
                    array_push($report_error, array(
                        "index" => $i,
                        "success" => 0,
                        "msg" => $error['msg'],
                        "email" => $error['email'],
                        "code" => $error['code']
                    ));
                }

                $i++;
            }

        }


        $count = count($response->return->result);

        $out['count'] = $count;
        $out['response'] = $response;
        $out['report_success'] = $report_success;
        $out['report_error'] = $report_error;
        $out['error_count'] = $error_count;

        return $out;
    }

}