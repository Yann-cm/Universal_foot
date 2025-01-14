<?php

function api_prediction($api_match){
    $curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);  
	curl_setopt_array($curl, [
		CURLOPT_URL => "https://api-football-v1.p.rapidapi.com/v3/predictions?fixture=".$api_match,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => [
			"X-RapidAPI-Host: api-football-v1.p.rapidapi.com",
			"X-RapidAPI-Key: f8c41ec7e3mshe2e6a4ccf486fc0p18eb21jsn8c26de7204b9"
		],
	]);

	$response = curl_exec($curl);

	curl_close($curl);
	return $response;
}
function api_info_team($api_team){

    $curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);  
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api-football-v1.p.rapidapi.com/v3/teams?id=". $api_team,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "x-rapidapi-host: api-football-v1.p.rapidapi.com",
            "x-rapidapi-key: f8c41ec7e3mshe2e6a4ccf486fc0p18eb21jsn8c26de7204b9"
        ],
    ]);
	$response = curl_exec($curl);

	curl_close($curl);

    return $response;
}
function api_info_joueur_team($api_team){
    $curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);  

	curl_setopt_array($curl, [
		CURLOPT_URL => "https://api-football-v1.p.rapidapi.com/v3/players/squads?team=". $api_team,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => [
			"X-RapidAPI-Host: api-football-v1.p.rapidapi.com",
			"X-RapidAPI-Key: f8c41ec7e3mshe2e6a4ccf486fc0p18eb21jsn8c26de7204b9"
		],
	]);
	
	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);

    return $response;

}
function api_info_team_nat($api_pays){
    $curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);  
	
	curl_setopt_array($curl, [
		CURLOPT_URL => "https://api-football-v1.p.rapidapi.com/v3/teams?country=".$api_pays,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => [
			"X-RapidAPI-Host: api-football-v1.p.rapidapi.com",
			"X-RapidAPI-Key: f8c41ec7e3mshe2e6a4ccf486fc0p18eb21jsn8c26de7204b9"
		],
	]);
	
	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);

    return $response;
}
function api_joueur_team_nat($api_pays){

    $curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);  

	curl_setopt_array($curl, [
		CURLOPT_URL => "https://api-football-v1.p.rapidapi.com/v3/players/squads?team=".$api_pays,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => [
			"X-RapidAPI-Host: api-football-v1.p.rapidapi.com",
			"X-RapidAPI-Key: f8c41ec7e3mshe2e6a4ccf486fc0p18eb21jsn8c26de7204b9"
		],
	]);

	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);

    return $response;
}
function api_match_league($api_league){
    $curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);  


	curl_setopt_array($curl, [
		CURLOPT_URL => "https://api-football-v1.p.rapidapi.com/v3/fixtures?league=".$api_league."&season=2024",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => [
			"x-rapidapi-host: api-football-v1.p.rapidapi.com",
			"x-rapidapi-key: f8c41ec7e3mshe2e6a4ccf486fc0p18eb21jsn8c26de7204b9"
		],
	]);
	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);

    return $response;

}
function api_pays(){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);  

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api-football-v1.p.rapidapi.com/v3/teams/countries",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: api-football-v1.p.rapidapi.com",
            "X-RapidAPI-Key: f8c41ec7e3mshe2e6a4ccf486fc0p18eb21jsn8c26de7204b9"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    return $response;
}
function api_info_league($api_league){
    $curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);  

	curl_setopt_array($curl, [
		CURLOPT_URL => "https://api-football-v1.p.rapidapi.com/v3/standings?league=".$api_league."&season=2024",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => [
			"x-rapidapi-host: api-football-v1.p.rapidapi.com",
			"x-rapidapi-key: f8c41ec7e3mshe2e6a4ccf486fc0p18eb21jsn8c26de7204b9"
		],
	]);



	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);
    return $response;
}