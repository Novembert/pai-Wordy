<?php

$loadProfileQuery = $connect->prepare("SELECT id_profilu,imie,nazwisko,opis,id_klasy FROM `profile` WHERE id_profilu=?");
$loadProfileQuery->bind_param('i',$data['3']);
$loadProfileQuery->execute();
$loadProfileResult = $loadProfileQuery->get_result();
$profile = mysqli_fetch_assoc($loadProfileResult);

// var_dump($profile);