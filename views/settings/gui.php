<?php
$this->Gui->col_width(1, 4);

$this->Gui->add_meta(array(
    'type'			=>    'unwrapped',
    'col_id'		=>    1,
    'namespace'	=>    'tailor_settings',
    'gui_saver'        =>        true,
    'footer'        =>        array(
        'submit'    =>        array(
            'label'    =>        __('Save Settings', 'tailor')
        )
    ),
));

$this->Gui->add_item([
    'type'      =>  'text',
    'name'      =>  store_prefix() . 'tailor_telegram_key',
    'description'   =>  __( 'Save your telegram key here', 'tailor' ),
    'label'     =>  __( 'Telegram Bot Key', 'tailor' )
], 'tailor_settings', '1' );

$this->Gui->add_item([
    'type'      =>  'dom',
    'content'   =>  $this->load->module_view( 'tailor', 'settings.ping', null, true )
], 'tailor_settings', 1 );

$this->Gui->output();