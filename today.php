<?php
	//Core file
	error_reporting(0);
	$dateType = 'en';
	$dateFormat = $_GET['dateFormat'];
	$dateLanguage = $_GET['dateLanguage'];
	$showTime = ($_GET['showTime']=='true')?true:false;
	$militaryTime = ($_GET['militaryTime']=='true')?true:false;	
	//
	date_default_timezone_set('Asia/Katmandu');
	include('nepali_calendar.php');
	$cal = new Nepali_Calendar();	
	$eflag=false;
	//	
	$date_arr=explode('-',date('Y-m-d'));
	if(!$cal->is_range_eng($date_arr[0],$date_arr[1],$date_arr[2]) && !$eflag)
	{
		$ret_op['error']=1;
		$ret_op['error_message']='Date out of range';
		$eflag=true;
	}
	
	if(!$eflag)
	{		
		$newd=$cal->eng_to_nep($date_arr[0],$date_arr[1],$date_arr[2]);
		
		if($dateLanguage=='np')
		{
			$newd=convertToNepali($newd);
		}
		$outpur_arr = $newd;	
		if($showTime == true)
		{
			if($militaryTime == true)	
				$current_time = date('H-i-s');
			else
				$current_time = date('h-i-s');
			list($time_hour, $time_minute, $time_second)=explode('-',$current_time);
			$outpur_arr['time_hour']	=	$time_hour;
			$outpur_arr['time_minute']	=	$time_minute;
			$outpur_arr['time_second']	=	$time_second;	
		}
	}
	else 
	{
		$outpur_arr =$ret_op;		
	}
	$json_encoded=json_encode($outpur_arr);
	$callback=$_GET['callback'];
	$op=$callback.'('.$json_encoded.')';
?>
<?php
	header('Content-type: text/html');	
	echo $op;
	//
	function convertToNepali($date)
	{
		$date['year']=getNepaliNumber($date['year']);
		$date['month_name']=getMahina($date['month']);
		$date['month']=getNepaliNumber($date['month']);
		$date['day']=getBaar($date['num_day']);
		$date['date']=getNepaliNumber($date['date']);				
		return $date;
	}
	//////////////
	function getNepaliNumber($num)
	{
		$str=array();
		$numarr=str_split($num);
		if(count($numarr)==1) array_unshift($numarr,'0');
		$number=array('०','१','२','३','४','५','६','७','८','९');			
		for($i=0;$i<count($numarr);$i++)
		{
			$str[$i]=$number[$numarr[$i]];
		}
		return  implode('',$str);
	}
	////////////////
	function getMahina($num)
	{
		$bar=array('बैशाख','जेठ','असार','साउन','भदौ','असोज','कार्तिक','मङि्सर','पुष','माघ','फागुन','चैत');			
		$ret=$bar[$num-1];
		return  $ret;
	}
	//////////////
	function getBaar($num)
	{
		$bar=array('आइतबार','सोमबार','मङ्गलबार','बुधबार','बिहिबार','शुक्रबार','शनिबार');			
		$ret=$bar[$num-1];
		return  ($ret);
	}
	//
?>
