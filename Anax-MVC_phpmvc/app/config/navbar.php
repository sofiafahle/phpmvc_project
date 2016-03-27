<?php
/**
 * Config-file for navigation bar.
 *
 */
 
$navbar = [

    // Use for styling the menu
    'class' => 'navbar',
 
    // Here comes the menu strcture
    'items' => [

        // This is a menu item
        'home'  => [
            'text'  => 'Home',
            'url'   => $this->di->get('url')->create(''),
            'title' => 'Startpage'
        ],
 
        // This is a menu item
        'questions'  => [
            'text'  => 'Questions',
            'url'   => $this->di->get('url')->create('question'),
            'title' => 'Read, post and answer to questions',
            'mark-if-parent-of' => 'question',

            // Here we add the submenu, with some menu items, as part of a existing menu item
            'submenu' => [

                'items' => [

                    // This is a menu item of the submenu
                    'All'  => [
                        'text'  => 'Show all',
                        'url'   => $this->di->get('url')->create('question/active'),
                        'title' => 'See all active questions'
                    ],

                    // This is a menu item of the submenu
                    'Add'  => [
                        'text'  => 'Add new',
                        'url'   => $this->di->get('url')->asset('question/add'),
                        'title' => 'Post a question',
                    ],
                ],
            ],
        ],
		

		// This is a menu item
        'tags'  => [
            'text'  => 'Tags',
            'url'   => $this->di->get('url')->create('tags'),
            'title' => 'Find items by tag',
            'mark-if-parent-of' => 'tags',

            // Here we add the submenu, with some menu items, as part of a existing menu item
            'submenu' => [

                'items' => [

                    // This is a menu item of the submenu
                    'All'  => [
                        'text'  => 'Show all',
                        'url'   => $this->di->get('url')->create('tags'),
                        'title' => 'See all active tags'
                    ],

                    // This is a menu item of the submenu
                    'Add'  => [
                        'text'  => 'Add new',
                        'url'   => $this->di->get('url')->asset('tags/add'),
                        'title' => 'Add a tag',
                    ],
                ],
            ],
        ],


        // This is a menu item
        'users' => [
            'text'  =>'Users',
            'url'   => $this->di->get('url')->create('users/active'),
            'title' => 'Show all users',
        ],
		
		// This is a menu item
        'about'  => [
            'text'  => 'About',
            'url'   => $this->di->get('url')->create('about'),
            'title' => 'About the page'
        ],
    ],
 


    /**
     * Callback tracing the current selected menu item base on scriptname
     *
     */
    'callback' => function ($url) {
        if ($url == $this->di->get('request')->getCurrentUrl(false)) {
            return true;
        }
    },



    /**
     * Callback to check if current page is a decendant of the menuitem, this check applies for those
     * menuitems that has the setting 'mark-if-parent' set to true.
     *
     */
    'is_parent' => function ($parent) {
        $route = $this->di->get('request')->getRoute();
        return !substr_compare($parent, $route, 0, strlen($parent));
    },



   /**
     * Callback to create the url, if needed, else comment out.
     *
     */
   /*
    'create_url' => function ($url) {
        return $this->di->get('url')->create($url);
    },
    */
];

if ($this->di->userInfo['id']) {
	// Add user navigation
	$navbar['items']['user'] = [
            'text'  =>'My profile',
            'url'   => $this->di->get('url')->create('users/id/' . $this->di->userInfo['id']),
            'title' => 'View your profile',
            'submenu' => [

                'items' => [

                    // This is a menu item of the submenu
                    'edit'  => [
                        'text'  => 'Edit profile',
                        'url'   => $this->di->get('url')->create('users/update/' . $this->di->userInfo['id']),
                        'title' => 'See all active tags'
                    ],
					
					// This is a menu item of the submenu
                    'posts'  => [
                        'text'  => 'See all posts',
                        'url'   => $this->di->get('url')->create('users/id-posts/' . $this->di->userInfo['id']),
                        'title' => 'See all your posts'
                    ],
                ],
            ],
        ];
		
	if (isset($this->di->userInfo['admin'])) {
		// Add admin navigation
		$navbar['items']['Admin'] = [
				'text'  =>'Admin',
				'url'   => $this->di->get('url')->create('admin'),
				'title' => 'Internal route within this frontcontroller',
				'submenu' => [
	
					'items' => [
	
						// This is a menu item of the submenu
						'questions'  => [
							'text'  => 'Questions',
							'url'   => $this->di->get('url')->asset('question'),
							'title' => 'Add a question',
							'mark-if-parent-of' => 'question',
							'submenu' => [
				
								'items' => [
				
									// This is a menu item of the submenu
									'All'  => [
										'text'  => 'Admin list',
										'url'   => $this->di->get('url')->create('question/list'),
										'title' => 'See all questions'
									],
				
									// This is a menu item of the submenu
									'Add'  => [
										'text'  => 'Add new',
										'url'   => $this->di->get('url')->asset('question/add'),
										'title' => 'Add a question',
									],
								],
							],
						],
						// This is a menu item
						'answers'  => [
							'text'  => 'Answers',
							'url'   => $this->di->get('url')->create('answer'),
							'title' => 'See all active answers',
							'mark-if-parent-of' => 'answer',
							'submenu' => [
				
								'items' => [
				
									// This is a menu item of the submenu
									'All'  => [
										'text'  => 'Admin list',
										'url'   => $this->di->get('url')->create('answer/list'),
										'title' => 'See all answers'
									],
								],
							],
						],
						// This is a menu item
						'comments'  => [
							'text'  => 'Comments',
							'url'   => $this->di->get('url')->create('comment'),
							'title' => 'List all comments',
							'mark-if-parent-of' => 'comment',
						],
						// This is a menu item of the submenu
						'tags'  => [
							'text'  => 'Tags',
							'url'   => $this->di->get('url')->create('tags'),
							'title' => 'See all active tags',
							'mark-if-parent-of' => 'tags',
							'submenu' => [
				
								'items' => [
				
									// This is a menu item of the submenu
									'All'  => [
										'text'  => 'Admin list',
										'url'   => $this->di->get('url')->create('tags/list'),
										'title' => 'See all tags'
									],
				
									// This is a menu item of the submenu
									'Add'  => [
										'text'  => 'Add new',
										'url'   => $this->di->get('url')->asset('tags/add'),
										'title' => 'Add a tag',
									],
								],
							],	
						],
					],
				],
			];
	}
	
	// Add log out button
	$navbar['items']['logout'] = [
		'text'  =>'Log out',
		'url'   => $this->di->get('url')->create('users/logout'),
		'title' => 'Log out from page',
	];
} else {
	// Add log in button
	$navbar['items']['login'] = [
		'text'  =>'Log in',
		'url'   => $this->di->get('url')->create('users/login'),
		'title' => 'Log on to page',
	];
	// Add registration link
	$navbar['items']['register'] = [
		'text'  =>'Register',
		'url'   => $this->di->get('url')->create('users/add'),
		'title' => 'Register to page',
	];
}

return $navbar;

