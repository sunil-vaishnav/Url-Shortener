<?php

function pr($data = null){
	echo "<pre>";
	print_r($data);
	echo "<pre>";
}

function prd($data = null){
	echo "<pre>";
	pr($data);
	exit;
}

function isSuperAdmin(){
	$currentUserRoles = auth()->user()->userRole;
	if($currentUserRoles->name == 'SuperAdmin'){
		return true;
	}

	return false;
}

function isAdmin(){
	$currentUserRoles = auth()->user()->userRole;
	if($currentUserRoles->name == 'Admin'){
		return true;
	}

	return false;
}

function isMember(){
	$currentUserRoles = auth()->user()->userRole;
	if($currentUserRoles->name == 'Member'){
		return true;
	}

	return false;
}