<?php
class ShowWeatherWidget extends Widget {
	public function render($cityId){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://www.weather.com.cn/data/cityinfo/101020100.html');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, "Content-type: text/plain; charset=UTF-8");
		$output = json_decode(curl_exec($ch), true);
		if (curl_errno($ch)) {
			print curl_error($ch);
		}
		curl_close($ch);
		
		if (preg_match('/([0-9]+)/', $output["weatherinfo"]["img1"], $img1)) {
			$output["weatherinfo"]["img1"] = __ROOT__.'/Public/Images/Weather/'.((int)$img1[0]).'.png';
		}
		if (preg_match('/([0-9]+)/', $output["weatherinfo"]["temp1"], $temp1)) {
			$output["weatherinfo"]["temp1"] = $temp1[0]."&#176;";
		}
		if (preg_match('/([0-9]+)/', $output["weatherinfo"]["temp2"], $temp2)) {
			$output["weatherinfo"]["temp2"] = $temp2[0]."&#176;";
		}
		
		//{"weatherinfo":{"city":"上海","cityid":"101020100","temp1":"15℃","temp2":"9℃","weather":"晴转多云","img1":"d0.gif","img2":"n1.gif","ptime":"11:00"}}
		$weather = array(	"num1"=>$output["weatherinfo"]["temp1"],
							"num2"=>$output["weatherinfo"]["temp2"], 		
							"txt"=>$output["weatherinfo"]["weather"], 
							"img"=>$output["weatherinfo"]["img1"]);
		return $weather;
	}
}
?>