<?php

namespace UserFrosting;

use \Illuminate\Database\Capsule\Manager as Capsule;

/**
 * xPromene Class
 *
 * CRU za promene
 *
 */

class xPromeneZahtev extends UFModel {
    /**
     * @var string The id of the table for the current model.
     */
    protected static $_table_id = "zahtevi";


    public function lista($app){

        $conn = Capsule::connection();
        $res = $conn->table('zahtevi')->orderBy('zid','desc')->get();


        $app->render('promeneZahtev.twig', [
            "paginate_server_side" => false,
            "promene" => $res
        ]);


    }

    // update aktera - process POST
    public function updateStatusZahteva($app){

        $conn = Capsule::connection();

        ///Validacija POST-a
        $post = $app->request->post();



        if(empty($_POST['zid'])){die('<div class="alert alert-danger">Error... Missing ID.</div>') ;} else {$zid = $_POST['zid'] ;}
        if(empty($_POST['status'])){die('<div class="alert alert-danger">Error... Missing STATUS.</div>') ;} else {$status = $_POST['status'] ;}

        //check if data in table is the same as data sent
        $rest =  $conn->table('zahtevi')->where('zid', '=', $zid)->where('zstatus', '=', $status)->count();
        //data submited is the same as data in database - notify OK
        if($rest){die('<div class="alert alert-success">Promena primenjena.</div>');}


        //UPDATE TABLE DATA
        $res =  $conn->table('zahtevi')->where('zid', '=', $zid)->update([  'zstatus' => $status  ]);


        if($res){
            die('<div class="alert alert-success">Promena primenjena.</div>');
            }else {
            die('<div class="alert alert-danger">Greska... Kontaktirajte podrsku...</div>');
            }

    }










    // list akter fields
    public function editPromene($app,$pid){

        $conn = Capsule::connection();
        $res = $conn->table('promene')->leftJoin('akteri', 'posoba', '=', 'aid')->leftJoin('stranke', 'pstranka', '=', 'sid')->leftJoin('funkcije', 'pfunkcija', '=', 'fid')->leftJoin('koalicije', 'pkoalicija', '=', 'kid')->leftJoin('opstine', 'popstina', '=', 'opid')->where('pid', '=', $pid)->get();


        $dump= "edit for no: ".$pid;
        //$dump= print_r($res,true);

        //$dump.= print_r($resakt,true);

        //akter
        $resakt = $conn->table('akteri')->orderBy('aprezime','asc')->get();
        $akter = '<select id="akter" name="akter"><option value=""></option>';
        foreach($resakt as $resa ){
            if($resa['aid']==$res[0]['posoba']) {$sela = "selected";}else{$sela = "";}
            $akter .= '
            <option '.$sela.' value="'.$resa['aid'].'">'.$resa['aprezime'].' '.$resa['aime'].'</option>';
        }
        $akter .= '</select>';

        //stranka
        $resstranka = $conn->table('stranke')->orderBy('snaziv','asc')->get();
        $stranka = '<select id="stranka" name="stranka"><option value="0">Stranka - nema podatka</option>';
        foreach($resstranka as $resstrankaa ){
            if($resstrankaa['sid']==$res[0]['pstranka']) {$sela = "selected";}else{$sela = "";}
            $stranka .= '
            <option '.$sela.' value="'.$resstrankaa['sid'].'">'.$resstrankaa['snaziv'].'</option>';
        }
        $stranka .= '</select>';


        //funkcija
        $resf = $conn->table('funkcije')->orderBy('funkcija','asc')->get();
        $funk = '<select id="funk" name="funk"><option value="0">Funkcija - nema podatka</option>';
        foreach($resf as $resfa ){
            if($resfa['fid']==$res[0]['pfunkcija']) {$sela = "selected";}else{$sela = "";}
            $funk .= '
            <option '.$sela.' value="'.$resfa['fid'].'">'.$resfa['funkcija'].'</option>';
        }
        $funk .= '</select>';

        //funkcija - mesto
        $resfm = $conn->table('funkcije_mesto')->orderBy('fmesto','asc')->get();
        $funkmesto = '<select id="fmesto" name="fmesto"><option value="0">Mesto funkcije - nema podatka</option>';
        foreach($resfm as $resfma ){
            if($resfma['fmid']==$res[0]['pfm']) {$sela = "selected";}else{$sela = "";}
            $funkmesto .= '
            <option '.$sela.' value="'.$resfma['fmid'].'">'.$resfma['fmesto'].'</option>';
        }
        $funkmesto .= '</select>';

        //koalicija
        $resk = $conn->table('koalicije')->orderBy('knaziv','asc')->get();
        $koal = '<select id="koalicija" name="koalicija"><option value="0">Koalicija - nema podatka</option>';
        foreach($resk as $reska ){
            if($reska['kid']==$res[0]['pkoalicija']) {$sela = "selected";}else{$sela = "";}
            $koal .= '
            <option '.$sela.' value="'.$reska['kid'].'">'.$reska['knaziv'].'</option>';
        }
        $koal .= '</select>';


        //opstina
        $reso = $conn->table('opstine')->orderBy('opstina','asc')->get();
        $ops = '<select id="opstina" name="opstina"><option value="0">Opstina - nema podatka</option>';
        foreach($reso as $resoa ){
            if($resoa['opid']==$res[0]['popstina']) {$sela = "selected";}else{$sela = "";}
            $ops .= '
            <option '.$sela.' value="'.$resoa['opid'].'">'.$resoa['opstina'].'</option>';
        }
        $ops .= '</select>';


        //na vlasti
        $vlast ='';$vlastsel='';$opozicija = '';$vlastdef='';
        if($res[0]['pnavlasti']==1){$vlastsel = 'selected';}  elseif($res[0]['pnavlasti']==2) {$opozicija = 'selected';} else {$vlastdef="selected";}

        $vlast = '<select id="vlast" name="vlast"><option  '.$vlastdef.'  value="0">Na vlasti - nema podatka</option>';
        $vlast .= '<option '.$vlastsel.' value="1">Vlast</option>';
        $vlast .= '<option '.$opozicija.' value="2">Opozicija</option>';
        $vlast .= '</select>';


        $pod = $res[0]['pod'];
        $pdo = $res[0]['pdo'];



        $app->render('promeneEdit.twig', [
            "paginate_server_side" => false,
            "dump" => $dump,
            "actionText" => "Update (ovo NE unosi novu promenu - samo menja postojecu)",
            "akter" => $akter,
            "stranka" => $stranka,
            "funk" => $funk,
            "funkmesto" => $funkmesto,
            "koal" => $koal,
            "ops" => $ops,
            "vlast" => $vlast,
            "pod" => $pod,
            "pdo" => $pdo,
            "promene" => $res
        ]);

    }



    // update aktera - process POST
    public function editPromenePost($app,$pid){
        $dump="";
//$dump .= "\r\n".print_r($_POST,true);
//echo $dump;

        $conn = Capsule::connection();

        ///Validacija POST-a
        $post = $app->request->post();
        // Load the request schema
        $requestSchema = new \Fortress\RequestSchema($app->config('schema.path') . "/forms/promene-update.json");
        // Get the alert message stream
        $ms = $app->alerts;
        // Set up Fortress to process the request
        $rf = new \Fortress\HTTPRequestFortress($ms, $requestSchema, $post);

        // Validate, and halt on validation errors.
        if (!$rf->validate()) {
            // MUST USE $app->alerts->getAndClearMessages()  to clear piled errors
            $app->render('alerts.twig', [
                "paginate_server_side" => false,
                "dump" => $dump,
                "alerts" => $app->alerts->getAndClearMessages()
            ]);
            die(); //$app->halt(400);

        }

////check if post same as existing data - prevent 0 on update
$restest = $conn->table('promene')->where('pid', '=', $pid)->get();
if( $restest[0]['posoba'] == $_POST['akter'] && $restest[0]['pstranka'] == $_POST['stranka'] && $restest[0]['pfunkcija'] == $_POST['funk'] && $restest[0]['pfm'] == $_POST['fmesto'] && $restest[0]['pkoalicija'] == $_POST['koalicija'] && $restest[0]['popstina'] == $_POST['opstina'] && $restest[0]['pnavlasti'] == $_POST['vlast'] && $restest[0]['pod'] == $_POST['altdatumod'] && $restest[0]['pdo'] == $_POST['altdatumdo']    )
{ die('<div class="alert alert-danger">Data you submited is the <b>SAME</b> as data in database. Aborting...</div>') ; }

if(empty($_POST['altdatumdo'])){$_POST['altdatumdo']= NULL;}

        //UPDATE TABLE DATA
        $res =  $conn->table('promene')->where('pid', '=', $pid)->update([  'posoba' => $_POST['akter'] , 'pstranka' => $_POST['stranka'] , 'pfunkcija' => $_POST['funk'] ,'pfm' => $_POST['fmesto'] , 'pkoalicija' => $_POST['koalicija'] , 'popstina' => $_POST['opstina'] , 'pnavlasti' => $_POST['vlast'], 'pod' => $_POST['altdatumod'] , 'pdo' => $_POST['altdatumdo']   ]);


        if($res){
            die('<div class="alert alert-success">Promena primenjena.</div>');
            }else {
            die('<div class="alert alert-danger">Promena NIJE primenjena... Kontaktirajte podrsku...</div>');
            }

    }




    // Novi akter - forma
    public function addPromena($app){

        $conn = Capsule::connection();

        $dump= "Dodaj novu promenu: ";
        //$dump= print_r($res,true);
        $dummyPromene = array(array());

//Nova promena
        //akter
        $resakt = $conn->table('akteri')->orderBy('aprezime','asc')->get();
        $akter = '<select id="akter" name="akter"><option value=""></option>';
        foreach($resakt as $resa ){
            $akter .= '
            <option  value="'.$resa['aid'].'">'.$resa['aprezime'].' '.$resa['aime'].'</option>';
        }
        $akter .= '</select>';

        //stranka
        $resstranka = $conn->table('stranke')->orderBy('snaziv','asc')->get();
        $stranka = '<select id="stranka" name="stranka"><option value="0">Stranka - nema podatka</option>';
        foreach($resstranka as $resstrankaa ){
            $stranka .= '
            <option  value="'.$resstrankaa['sid'].'">'.$resstrankaa['snaziv'].'</option>';
        }
        $stranka .= '</select>';


        //funkcija
        $resf = $conn->table('funkcije')->orderBy('funkcija','asc')->get();
        $funk = '<select id="funk" name="funk"><option value="0">Funkcija - nema podatka</option>';
        foreach($resf as $resfa ){
            $funk .= '
            <option  value="'.$resfa['fid'].'">'.$resfa['funkcija'].'</option>';
        }
        $funk .= '</select>';

        //funkcija - mesto
        $resfm = $conn->table('funkcije_mesto')->orderBy('fmesto','asc')->get();
        $funkmesto = '<select id="fmesto" name="fmesto"><option value="0">Mesto funkcije - nema podatka</option>';
        foreach($resfm as $resfma ){
            //if($resfma['fmid']==$res[0]['pfm']) {$sela = "selected";}else{$sela = "";}
            $funkmesto .= '
            <option  value="'.$resfma['fmid'].'">'.$resfma['fmesto'].'</option>';
        }
        $funkmesto .= '</select>';

        //koalicija
        $resk = $conn->table('koalicije')->orderBy('knaziv','asc')->get();
        $koal = '<select id="koalicija" name="koalicija"><option value="0">Koalicija - nema podatka</option>';
        foreach($resk as $reska ){
            $koal .= '
            <option  value="'.$reska['kid'].'">'.$reska['knaziv'].'</option>';
        }
        $koal .= '</select>';


        //opstina
        $reso = $conn->table('opstine')->orderBy('opstina','asc')->get();
        $ops = '<select id="opstina" name="opstina"><option value="0">Opstina - nema podatka</option>';
        foreach($reso as $resoa ){
            $ops .= '
            <option  value="'.$resoa['opid'].'">'.$resoa['opstina'].'</option>';
        }
        $ops .= '</select>';


        //na vlasti
        $vlast = '<select id="vlast" name="vlast"><option value="0">Na vlasti - nema podatka</option>';
        $vlast .= '<option  value="1">Vlast</option>';
        $vlast .= '<option  value="2">Opozicija</option>';
        $vlast .= '</select>';







        $app->render('promeneEdit.twig', [
            "paginate_server_side" => false,
            "dump" => $dump,
            "actionText" => "Add New",
            "akter" => $akter,
            "stranka" => $stranka,
            "funk" => $funk,
            "funkmesto" => $funkmesto,
            "koal" => $koal,
            "ops" => $ops,
            "vlast" => $vlast,
            "pod" => '',
            "pdo" => '',
            "promene" => $dummyPromene
        ]);

    }





    // Nova promena - process POST
    public function addPromenaPost($app){

        $conn = Capsule::connection();
        $dump="";

        ///Validacija POST-a
        $post = $app->request->post();
        // Load the request schema
        $requestSchema = new \Fortress\RequestSchema($app->config('schema.path') . "/forms/promene-update.json");


        // Get the alert message stream
        $ms = $app->alerts;
        // Set up Fortress to process the request
        $rf = new \Fortress\HTTPRequestFortress($ms, $requestSchema, $post);

        // Validate, and halt on validation errors.
        if (!$rf->validate()) {
            // MUST USE $app->alerts->getAndClearMessages()  to clear piled errors
            $app->render('alerts.twig', [
                "paginate_server_side" => false,
                "dump" => $dump,
                "alerts" => $app->alerts->getAndClearMessages()
            ]);
            die(); //$app->halt(400);

        }

//$dump .= "\r\n".print_r($_POST,true);
//echo $dump;
//die();

if(empty($_POST['altdatumdo'])){$_POST['altdatumdo']= NULL;}

        //insert data
        $res =  $conn->table('promene')->insert([  'posoba' => $_POST['akter'] , 'pstranka' => $_POST['stranka'] , 'pfunkcija' => $_POST['funk'] ,'pfm' => $_POST['fmesto'] , 'pkoalicija' => $_POST['koalicija'] , 'popstina' => $_POST['opstina'] , 'pnavlasti' => $_POST['vlast'], 'pod' => $_POST['altdatumod'] , 'pdo' => $_POST['altdatumdo']  ]);

        if($res){
            die('<div class="alert alert-success">Nova promena dodata.</div>');
        }else {
            die('<div class="alert alert-danger">Nova promena NIJE dodata... Kontaktirajte podrsku.</div>');
        }


    }



}



?>