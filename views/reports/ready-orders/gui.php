<?php
$this->Gui->col_width(1, 4);

$this->Gui->add_meta(array(
     'type'			=>    'unwrapped',
     'col_id'		=>    1,
     'namespace'	=>    'tailor_ready_order'
));

$this->Gui->add_item( array(
     'type'          =>    'dom',
     'content'       =>    $this->load->module_view( 'tailor', 'reports.ready-orders.dom', null, true )
), 'tailor_ready_order', 1 );

$this->Gui->output();