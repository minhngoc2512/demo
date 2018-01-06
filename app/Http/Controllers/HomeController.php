<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yangqi\Htmldom\Htmldom;

class HomeController extends Controller
{
    public function index(){
        $html = new Htmldom('https://www.11v11.com/teams/manchester-united/tab/matches/season/2016/');
        $data_1 = $html->find("tbody tr");
        $i=0;
        $length_arr = count($data_1);
        $i=1;
        foreach ($data_1 as $key=>$e){
            if($key==0){
                continue;
            }
//            echo $e->children(0)->plaintext.'<br>';

//            echo $e->children(0)->plaintext;
            echo($e->children(0)->plaintext.','.$e->children(1)->plaintext.','.$e->children(2)->plaintext.','.$e->children(3)->plaintext.','.$e->children(4)->plaintext.'<br>');
        }
        dd();

        dd();
        $data = file_get_contents(base_path('/storage/dataapp/index.html'));
        $data = $this->minifyHtml($data);
        preg_match_all('/<tbody><tr>/',$data,$result);
        dd($result);
        return 'sdf';
    }

    public function getMatch(){
        //- Matches: date, team(home,away),result, score,competition,season,detail_match.
        for($i=1890;$i<=2018;$i++){
            $html = new Htmldom("https://www.11v11.com/teams/manchester-united/tab/matches/season/$i/");
            $html_arr = $html->find("tbody tr");
            foreach($html_arr as $key=>$result){
                if($key==0){
                    continue;
                }
                echo($result->children(0)->plaintext.','.$result->children(1)->plaintext.','.$result->children(2)->plaintext.','.$result->children(3)->plaintext.','.$result->children(4)->plaintext.'<br>');


            }
            echo "<br><h1>$i</h1><br><br>";
            if($i==1900){
                break;
            }

        }

    }
    function insertMatchDB($date,$teams,$result,$score,$competition,$season,$detail_match){

    }


    function minifyHtml($content) {

        $search = array(
            '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
            '/[^\S ]+\</s',     // strip whitespaces before tags, except space
            '/(\s)+/s',         // shorten multiple whitespace sequences
            '/<!--(.|\s)*?-->/' // Remove HTML comments
        );

        $replace = array(
            '>',
            '<',
            '\\1',
            ''
        );

        $content = preg_replace($search, $replace, $content);

        return $content;
    }
    //
}
