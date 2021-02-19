<?php

function trimpp($str)
{
	return preg_replace('/\s+/', ' ', trim($str));;
}
