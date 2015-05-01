<?
return array(
    'default_menu' => array(),
    'template_class' => "Admin_Login_Site_Template",
    'component_slots' => array(
        'login' => 'autentikacio',
        'messages' => 'uzenetek'
    ),
    'components' => array(
        'autentikacio' => array(
            'class' => 'AutentikacioKomponens',
            'allowed_slots' => array()
        ),
        'uzenetek' => array(
            'class' => 'Uzenetek_Site_Component',
            'allowed_slots' => array()
        )
    )
);