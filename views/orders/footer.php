<script>
$( '.toggle-tailor' ).bind( 'click', function( e ) {
    const tailorOrdersData  =   {
        status: <?php echo json_encode( $this->config->item( 'nexo_orders_status' ) );?>,
        textDomain: {
            tailorOptions:  `<?php echo __( 'Tailor Options', 'tailor' );?>`,
            settings:  `<?php echo __( 'Settings', 'tailor' );?>`,
            shirt:  `<?php echo __( 'Shirt Measures', 'tailor' );?>`,
            pant:  `<?php echo __( 'Pant Measures', 'tailor' );?>`,
            preview:  `<?php echo __( 'Preview', 'tailor' );?>`,
            deliveryDate:  `<?php echo __( 'Delivery Date', 'tailor' );?>`,
            priority:  `<?php echo __( 'Priority', 'tailor' );?>`,
            assignated:  `<?php echo __( 'Assignated', 'tailor' );?>`,
            expirationDate:  `<?php echo __( 'Expiration Date', 'tailor' );?>`,
            status:  `<?php echo __( 'Status', 'tailor' );?>`,
            changeStatus:  `<?php echo __( 'Change Status', 'tailor' );?>`,
            ongoing:  `<?php echo __( 'In Progress', 'tailor' );?>`,
            ready:  `<?php echo __( 'Ready', 'tailor' );?>`,
            change:  `<?php echo __( 'Change', 'tailor' );?>`,
            shirt: `<?php echo __( 'Shirt', 'tailor' );?>`,
            pant: `<?php echo __( 'Pant', 'tailor' );?>`,
            style : `<?php echo __( 'Style', 'tailor' );?>`,
            aroundNeck: `<?php echo __( 'Around Neck', 'tailor' );?>`,
            neckShoulderLength: `<?php echo __( 'Neck Shoulder', 'tailor' );?>`,
            aroundWaist : `<?php echo __( 'Around Waist', 'tailor' );?>`,
            aroundHip : `<?php echo __( 'Around Hip', 'tailor' );?>`,
            frontRise : `<?php echo __( 'Front Rise', 'tailor' );?>`,
            aroundThigh : `<?php echo __( 'Around Thigh', 'tailor' );?>`,
            length : `<?php echo __( 'Length', 'tailor' );?>`,
            inseam : `<?php echo __( 'Inseam', 'tailor' );?>`,
            aroundKnee : `<?php echo __( 'Around Knee', 'tailor' );?>`,
            legOpening : `<?php echo __( 'Leg Opening', 'tailor' );?>`,
            delivery : `<?php echo __( 'Delivery', 'tailor' );?>`,
            shoulderShoulder: `<?php echo __( 'Shoulder-Shoulder', 'tailor' );?>`,
            aroundChest: `<?php echo __( 'Around Chest', 'tailor' );?>`,
            sleeveLength: `<?php echo __( 'Sleeve Length', 'tailor' );?>`,
            sleeveCuff: `<?php echo __( 'Sleeve Cuff', 'tailor' );?>`,
            aroundWaist: `<?php echo __( 'Around Waist', 'tailor' );?>`,
            frontLength: `<?php echo __( 'Front Length', 'tailor' );?>`,
            backLength: `<?php echo __( 'Back Length', 'tailor' );?>`,
            measureHip: `<?php echo __( 'Measure Hip', 'tailor' );?>`,
            measureShirtHem: `<?php echo __( 'Measure Shirt Hem', 'tailor' );?>`,
            customerMeasures: `<?php echo __( 'Customer Measures', 'tailor' );?>`,
            chooseValue: `<?php echo __( 'Choose Value', 'tailor' );?>`,
            normal: `<?php echo __( 'Normal', 'tailor' );?>`,
            medium: `<?php echo __( 'Medium', 'tailor' );?>`,
            high: `<?php echo __( 'High', 'tailor' );?>`,
            deliveryDate: `<?php echo __( 'Delivery Date', 'tailor' );?>`,
            assignedTailor: `<?php echo __( 'Assigned Tailor', 'tailor' );?>`,
            priority: `<?php echo __( 'Priority', 'tailor' );?>`,
            noMeasureDefined: `<?php echo __( 'No Measure has been defined. Please define some before submitting the order.', 'tailor' );?>`,
            missingDeliveryDate: `<?php echo __( 'The delivery date has not been defined. Please define the date before saving the order.', 'tailor' );?>`,
            expirationDate: `<?php echo __( 'Expiration Date', 'tailor' );?>`,
            pending: `<?php echo __( 'Pending', 'tailor' );?>`,
            assignedImage: `<?php echo __( 'Assigned Image', 'tailor' );?>`,
            measureDefined: `<?php echo __( 'The measure has been defined.', 'tailor' );?>`,
            noPreviewProvided: `<?php echo __( 'No Preview is provided.', 'tailor' );?>`,
            unknownStatus: `<?php echo __( 'Unknown Status.', 'tailor' );?>`,
            delivered: `<?php echo __( 'Delivered', 'tailor' );?>`,
            unknownError:  `<?php echo __( 'An unexpected error occured.', 'tailor' );?>`,
        },
        url : {
            fetchOrders: `<?php echo site_url([ 'api', 'tailor', 'orders', '{id}', store_get_param( '?' ) ]);?>`,
            saveStatus: `<?php echo site_url([ 'api', 'tailor', 'orders', '{id}', store_get_param( '?' ) ]);?>`,
        }
    }

    const orderID   =   $( this ).closest( 'tr' ).find( 'input[type="checkbox"]').val();

    NexoAPI.Bootbox().confirm( `
        <div id="tailor-options" v-if="tailor">
            <h3 class="text-center">${tailorOrdersData.textDomain.tailorOptions}</h3>
            <ul class="nav nav-tabs tab-grid">
                <li @click="selectedTab = 'settings'" :class="{ 'active' : selectedTab === 'settings' }"><a href="javascript:void(0)">${tailorOrdersData.textDomain.settings}</a></li>
                <li @click="selectedTab = 'pant'" :class="{ 'active' : selectedTab === 'pant' }"><a href="javascript:void(0)">${tailorOrdersData.textDomain.shirt}</a></li>
                <li @click="selectedTab = 'shirt'" :class="{ 'active' : selectedTab === 'shirt' }"><a href="javascript:void(0)">${tailorOrdersData.textDomain.pant}</a></li>
                <li @click="selectedTab = 'preview'" :class="{ 'active' : selectedTab === 'preview' }"><a href="javascript:void(0)">${tailorOrdersData.textDomain.preview}</a></li>
            </ul>
            <div style="padding: 10px" v-if="selectedTab === 'settings'">
                <div class="row">
                    <div class="col-sm-6">
                        <div>
                            <h3>{{ textDomain.deliveryDate }}</h3>
                            <p>{{ tailor.order.TAILOR_DELIVERY_DATE }}</p>
                        </div>
                        <div>
                            <h3>{{ textDomain.priority }}</h3>
                            <p>{{ tailor.order.TAILOR_PRIORITY }}</p>
                        </div>
                        <div>
                            <h3>{{ textDomain.assignated }}</h3>
                            <p>{{ tailor.assignated.name }}</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div>
                            <h3>{{ textDomain.expirationDate }}</h3>
                            <p>{{ tailor.order.TAILOR_EXPIRATION_DATE }}</p>
                        </div>
                        <div>
                            <h3>{{ textDomain.status }}</h3>
                            <p>{{ status[ tailor.order.STATUS ] || textDomain.unknownStatus }}</p>
                        </div>
                        <div>
                            <h3>{{ textDomain.changeStatus }}</h3>
                            <p>
                                <div class="input-group">
                                    <select v-model="orderStatus" type="text" class="form-control" placeholder="Username" aria-describedby="sizing-addon2">
                                        <option value="pending">{{ textDomain.pending }}</option>
                                        <option value="processing">{{ textDomain.ongoing }}</option>
                                        <option value="completed">{{ textDomain.ready }}</option>
                                        <option value="delivered">{{ textDomain.delivered }}</option>
                                    </select>
                                    <span class="input-group-btn">
                                        <button @click="saveStatus()" class="btn btn-default" type="button">{{ textDomain.change }}</button>
                                    </span>
                                </div>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div style="padding: 10px" v-if="selectedTab === 'pant'">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item"><strong>{{ textDomain.style }}</strong> : {{ tailor.measures.pant.STYLE }}</li>
                            <li class="list-group-item"><strong>{{ textDomain.aroundWaist }}</strong> : {{ tailor.measures.pant.AROUND_WAIST }}</li>
                            <li class="list-group-item"><strong>{{ textDomain.aroundHip }}</strong> : {{ tailor.measures.pant.AROUND_HIP }}</li>
                            <li class="list-group-item"><strong>{{ textDomain.frontRise }}</strong> : {{ tailor.measures.pant.FRONT_RISE }}</li>
                            <li class="list-group-item"><strong>{{ textDomain.aroundThigh }}</strong> : {{ tailor.measures.pant.AROUND_THIGH }}</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item"><strong>{{ textDomain.length }}</strong> : {{ tailor.measures.pant.LENGTH }}</li>
                            <li class="list-group-item"><strong>{{ textDomain.inseam }}</strong> : {{ tailor.measures.pant.INSEAM }}</li>
                            <li class="list-group-item"><strong>{{ textDomain.aroundKnee }}</strong> : {{ tailor.measures.pant.AROUND_KNEE }}</li>
                            <li class="list-group-item"><strong>{{ textDomain.legOpening }}</strong> : {{ tailor.measures.pant.LEG_OPENING }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div style="padding: 10px" v-if="selectedTab === 'shirt'">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item"><strong>{{ textDomain.style }}</strong> : {{ tailor.measures.shirt.STYLE }}</li>
                            <li class="list-group-item"><strong>{{ textDomain.aroundNeck }}</strong> : {{ tailor.measures.shirt.AROUND_NECK }}</li>
                            <li class="list-group-item"><strong>{{ textDomain.neckShoulderLength }}</strong> : {{ tailor.measures.shirt.NECK_SHOULDER_LENGTH }}</li>
                            <li class="list-group-item"><strong>{{ textDomain.shoulderShoulder }}</strong> : {{ tailor.measures.shirt.SHOULDER_SHOULDER }}</li>
                            <li class="list-group-item"><strong>{{ textDomain.aroundChest }}</strong> : {{ tailor.measures.shirt.AROUND_CHEST }}</li>
                            <li class="list-group-item"><strong>{{ textDomain.sleeveLength }}</strong> : {{ tailor.measures.shirt.SLEEVE_LENGTH }}</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item"><strong>{{ textDomain.sleeveCuff }}</strong> : {{ tailor.measures.shirt.SLEEVE_CUFF }}</li>
                            <li class="list-group-item"><strong>{{ textDomain.aroundWaist }}</strong> : {{ tailor.measures.shirt.AROUND_WAIST }}</li>
                            <li class="list-group-item"><strong>{{ textDomain.frontLength }}</strong> : {{ tailor.measures.shirt.FRONT_LENGTH }}</li>
                            <li class="list-group-item"><strong>{{ textDomain.backLength }}</strong> : {{ tailor.measures.shirt.BACK_LENGTH }}</li>
                            <li class="list-group-item"><strong>{{ textDomain.measureHip }}</strong> : {{ tailor.measures.shirt.MEASURE_HIP }}</li>
                            <li class="list-group-item"><strong>{{ textDomain.measureShirtHem }}</strong> : {{ tailor.measures.shirt.MEASURE_SHIRT_HEM }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div style="padding: 10px" v-if="selectedTab === 'preview'">
                <div v-if="tailor.order.ASSIGNED_IMAGE === ''">{{ textDomain.noPreviewProvided }}</div>
                <img v-else :src="tailor.order.ASSIGNED_IMAGE" style="max-width:100%">
            </div>
        </div>
    `, ( action ) => {
        if ( action ) {
            
        }
    });

    const tailorOptions     =   new Vue({
        el: '#tailor-options',
        data: {
            selectedTab: 'settings',
            ...tailorOrdersData,
            tailor: false,
            detailsFetched: false,
            orderStatus: '',
        },
        mounted() {
            this.fetchOrders();
        },
        methods: {
            fetchOrders() {
                HttpRequest.get( this.url.fetchOrders.replace( '{id}', orderID ) ).then( result => {
                    this.tailor     =   result.data;
                })
            },
            saveStatus() {
                HttpRequest.post( this.url.saveStatus.replace( '{id}', orderID ), {
                    status : this.orderStatus
                }).then( result => {
                    NexoAPI.Toast()( result.data.message );
                    this.fetchOrders();
                }).catch( error => {
                    NexoAPI.Toast()( error.response.data.message || this.textDomain.unknownError );
                })
            }
        }
    })

    e.preventDefault();
});
</script>