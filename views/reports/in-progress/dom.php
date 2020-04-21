<div id="in-progress">
    <form class="form-inline hidden-print">
        <date-picker label="<?php echo __( 'Début', 'nexo_premium' );?>" date-format="YYYY-MM-DD" @changed="changeStartDate( $event )" :value="startDate" v-once></date-picker>
        <date-picker label="<?php echo __( 'Fin', 'nexo_premium' );?>" date-format="YYYY-MM-DD" @changed="changeEndDate( $event )" :value="endDate" v-once></date-picker>
        <input 
            type="button" 
            class="btn btn-primary" 
            @click="getReport()"
            value="<?php _e('Afficher les résultats', 'nexo_premium');?>" />
        <div class="input-group">
            <span class="input-group-btn">
                <button class="btn btn-default" print-item=".report-wrapper"
                    type="button"><?php _e('Imprimer', 'nexo_premium');?></button>
            </span>
        </div>
    </form>
    <br>
    <div class="box">
        <div class="box-body no-padding">
            <table class="table">
            <thead>
                    <tr>
                        <td><?php echo __( 'Order', 'tailor' );?></td>
                        <td><?php echo __( 'Title', 'tailor' );?></td>
                        <td><?php echo __( 'Tailor', 'tailor' );?></td>
                        <td><?php echo __( 'Customer', 'tailor' );?></td>
                        <td><?php echo __( 'Delivery Date', 'tailor' );?></td>
                        <td><?php echo __( 'Expiration Date', 'tailor' );?></td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="entry of entries">
                        <td>{{ entry.CODE }}</td>
                        <td>{{ entry.TITRE }}</td>
                        <td>{{ entry.tailor.name }}</td>
                        <td>{{ entry.customer.name }}</td>
                        <td>{{ entry.TAILOR_DELIVERY_DATE }}</td>
                        <td>{{ entry.TAILOR_EXPIRATION_DATE }}</td>
                    </tr>
                    <tr v-if="entries.length === 0">
                        <td colspan="5"><?php echo __( 'There is no entry to display...', 'tailor' );?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    Vue.filter( 'moneyFormat', function( value ) {
        return NexoAPI.DisplayMoney( value );
    });

    var datepickerComponent = Vue.component( 'date-picker', {
        //v-el:select
        template: `
        <div class="input-group date" v-el:inputgroup>
            <span class="input-group-addon">{{ label }}</span>
            <input type="text" class="form-control" v-model="value">
            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
        </div>
        `,
        props: [ 'value', 'dateFormat', 'label' ],
        data: function() {
            return {};
        },
        mounted() {
            this.label      =   this.label || 'Date';
            $(this.$el).datetimepicker({
                format: this.dateFormat || 'YYYY-MM-DD'
            });
            $( this.$el ).on( 'dp.change', ( e ) => {
                this.startDate  =   $( e.currentTarget ).find( 'input' ).val();
                this.$emit( 'changed', this.startDate );
            })
        },
        beforeDestroy: function() {
            $(this.$el).datepicker('hide').datepicker('destroy');
        }
    });
</script>
<script src="<?php echo site_url( 'public/modules/nexo/bower_components/moment/min/moment.min.js' );?>"></script>
<script src="<?php echo site_url( 'public/modules/nexo/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js' );?>"></script>
<script>
const InProgressData    =   {
    url : {
        fetchReport : `<?php echo site_url([ 'api', 'tailor', 'orders', store_get_param( '?' )]);?>`
    },
    textDomain: {
        unexpectedErrorOccured : `<?php echo __( 'An unexpected error has occured', 'tailor' );?>`
    }
}
</script>
<script>
const InProgress    =   new Vue({
    el: '#in-progress',
    data: {
        startDate: '',
        endDate: '',
        ...InProgressData,
        entries: [],
    },
    methods : {
        getReport() {
            HttpRequest.post( this.url.fetchReport, {
                startDate : this.startDate,
                endDate : this.endDate,
                filter: 'in-progress'
            }).then( result => {
                this.entries    =   result.data;
            }).catch( error => {
                NexoAPI.Toast()( error.response.data.message || this.textDomain.unexpectedErrorOccured );
            })
        },
        changeStartDate( event ) {
            this.startDate  =   event;
        },
        changeEndDate( event ) {
            this.endDate    =   event;
        },
    }
})
</script>