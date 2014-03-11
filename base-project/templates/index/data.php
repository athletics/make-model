<?php

$articles = $app['client']->get_items( 'blog', array(
	'limit' => 10,
	'random' => true,
) );

return array(
	'articles' => $articles,
);