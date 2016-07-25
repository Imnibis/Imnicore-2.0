<?php

//	 _________	  ___    ___    ___     __    _________     _______       __|__|__|__|__      ________       __________
//	|___   ___|  |   \  /   |  |   \   |  |  |___   ___|   /  ___  \     /              \    |   ___  \     |   _______|
//	    | |	 	 |    \/    |  |    \  |  |      | |      |  /   \__|   |  °          °  |   |  |   \  \    |  |
//      | |      |  |    |  |  |  |  \ |  |      | |      |  |        --|                |-- |  |___/  /    |  |_____
//		| |		 |  |\__/|  |  |  |\  \|  |      | |      |  |        --|                |-- |   __   /     |   _____|
//		| |		 |  |    |  |  |  | \  \  |      | |      |  |        --|                |-- |  |  \  \     |  |
//		| |		 |  |    |  |  |  |  \    |      | |      |  |    __  --|                |-- |  |   \  \    |  |
//	 ___| |___	 |  |    |  |  |  |   \   |   ___| |___   |  \___/  |   |  o          o  |   |  |    \  \   |  |_______
//	|_________|	 |__|    |__|  |__|    \__|  |_________|   \_______/     \______________/    |__|     \__\  |__________|
//  2.0 par Imnibis                                                         |  |  |  |


require('lib/errorHandler.php');
require('model/database.php');
require('imnicore.php');
require('lib/dynscripts.php');
require('lib/lang.php');
require('lib/controller.php');
require('lib/response.php');
require('lib/globals.php');

Globals::init();
$response = new Response();