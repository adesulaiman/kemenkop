<?php

session_start();
if(session_destroy())
{
	echo json_encode('./login.php');
}

