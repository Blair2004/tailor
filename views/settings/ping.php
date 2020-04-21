<div class="ping-app">
    <button class="btn btn-primary"><?php echo __( 'Register Webhook', 'tailor' );?></button>
    <p><?php echo sprintf(
        __( 'Telegram will communicate with this installation via <pre>%s</pre>', 'tailor' ),
        site_url( 'tailor/telegram/' . store_slug() )
    );?></p>
</div>
<script>
const pingAppData   =   {
    url : {
        pingTelegram: `<?php echo site_url([ 'api', 'tailor', 'ping-telegram', store_get_param( '?' ) ]);?>`
    }
};
const pingApp   =   new Vue({
    el: '.ping-app',
    methods: {
        pingTelegram() {
            HttpRequest.get( this.url.pingTelegram ).then( result => {
                
            })
        }
    }
})
</script>