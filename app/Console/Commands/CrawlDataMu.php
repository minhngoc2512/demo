<?php

namespace App\Console\Commands;

use ErrorException;
use Illuminate\Console\Command;
use Yangqi\Htmldom\Htmldom;
use App\MatchHistory;
use Carbon\Carbon;
class CrawlDataMu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawlDataMu:insertDB {option} {--year=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get data Match of Mu';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    public $year_error =[];
    public $year =0;
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $option =  $this->argument('option');
        $this->year = $this->option('year');


        if($option=='match'){
            $this->info('Getting data match of MU....');
            $this->getMatch();
        }
    }
    public function getMatch(){
        if($this->year!=null){
            $i = $this->year;
            $this->warn('YEAR:'.$i);
            try {
                $this->info('Connecting: ' . "https://www.11v11.com/teams/manchester-united/tab/matches/season/$i/");
                $html = new Htmldom("https://www.11v11.com/teams/manchester-united/tab/matches/season/$i/");
            }catch(ErrorException $e){
                array_push($this->year_error,$i);
                $this->error($e);
                $this->info("Connecting again...");
            }
            $html_arr = $html->find("tbody tr");
            foreach($html_arr as $key=>$result){
                if($key==0){
                    continue;
                }
                $this->insertMatchDB($result->children(0)->plaintext,$result->children(1)->plaintext,$result->children(2)->plaintext,$result->children(3)->plaintext,$result->children(4)->plaintext,$i);
//                $this->info($result->children(0)->plaintext.','.$result->children(1)->plaintext.','.$result->children(2)->plaintext.','.$result->children(3)->plaintext.','.$result->children(4)->plaintext,$i);


            }
            $this->info("Finish!!!");
            return;
        }
        for($i=1890;$i<=2018;$i++){
            $this->warn('YEAR:'.$i);
            try {
                $this->info('Connecting: ' . "https://www.11v11.com/teams/manchester-united/tab/matches/season/$i/");
                $html = new Htmldom("https://www.11v11.com/teams/manchester-united/tab/matches/season/$i/");
            }catch(ErrorException $e){
                array_push($this->year_error,$i);
                $this->error($e);
                $this->info("Connecting again...");
                continue;
            }
            $html_arr = $html->find("tbody tr");
            foreach($html_arr as $key=>$result){
                if($key==0){
                    continue;
                }
                $this->insertMatchDB($result->children(0)->plaintext,$result->children(1)->plaintext,$result->children(2)->plaintext,$result->children(3)->plaintext,$result->children(4)->plaintext,$i);
//                $this->info($result->children(0)->plaintext.','.$result->children(1)->plaintext.','.$result->children(2)->plaintext.','.$result->children(3)->plaintext.','.$result->children(4)->plaintext);


            }
//            if($i==1900){
//                break;
//            }

        }
        $this->info("=======");
        $this->warn("Finish! Error: ".count($this->year_error));
        if(count($this->year_error)>0){
            $this->error('Year error:');
            dd($this->year_error);
        }
        $this->info("=======");
        return;

    }
    function insertMatchDB($date,$teams,$result,$score,$competition,$season){
        $score =str_slug($score);
        $result = str_replace(' ','',$result);
        $season_2 =(int) substr($season,2,4);
        $season = (int) $season;
        $y_1 = $season - 1;
        $season = $y_1.'-'.$season_2;
        $date = Carbon::parse($date);
        $teams_arr = explode(' v ',$teams);
        $home_team = $teams_arr[0];
        $away_team = $teams_arr[1];
        $match =new MatchHistory();
        $match->date = $date;
        $match->home_team = $home_team;
        $match->away_team = $away_team;
        $match->result = $result;
        $match->competition =$competition;
        $match->score = $score;
        $match->season = $season;
        $match->save();
        $this->info("date:$date,team:$teams,result:$result,score:$score,season:$season");
        //- Matches: date, team(home,away),result, score,competition,season,detail_match.
    }
}
