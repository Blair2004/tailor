<div id="tailor">

</div>
<script>
const TailorAppData     =   {
    tailors: <?php echo json_encode( $this->auth->list_users( 'tailor.worker' ) );?>,
    textDomain: {
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
        assignedImage: `<?php echo __( 'Assigned Image', 'tailor' );?>`,
        measureDefined: `<?php echo __( 'The measure has been defined.', 'tailor' );?>`,
    }
}
const TailorApp     =   new Vue({
    el : '#tailor',
    mounted() {
        $( '.tailor' ).bind( 'click', () => this.openPopup() );
        NexoAPI.events.addFilter( 'nexo_open_save_box', () => this.checkMeasures( 'save' ) );
        NexoAPI.events.addFilter( 'openPayBox', () => this.checkMeasures( 'payment' ) );
        NexoAPI.events.addAction( 'open_order_on_pos', ( details ) => this.populateMeasure( details ) );
    },
    data: {
        ...TailorAppData,
    },
    methods: {
        populateMeasure( details ) {
            const measures  =   details.measures;
            let { state, priority, expiration_date, delivery, assigned_tailor }   =   details.measures;

            expiration_date     =   moment( expiration_date, 'MM/DD/YYYY' ).format( 'YYYY-MM-DD' );
            delivery            =   moment( delivery, 'MM/DD/YYYY' ).format( 'YYYY-MM-DD' );
            
            v2Checkout.CartMetas[ 'tailor' ]    =   { pant: {}, shirt: {}, priority, expiration_date, delivery, assigned_tailor };
            for( key in measures.pant )  {
                v2Checkout.CartMetas[ 'tailor' ].pant[ key.toLowerCase() ]  =   measures.pant[ key ];
            }
            for( key in measures.shirt )  {
                v2Checkout.CartMetas[ 'tailor' ].shirt[ key.toLowerCase() ]  =   measures.shirt[ key ];
            }
        },  
        checkMeasures( action ) {
            const result    =   this.isMeasuresDefined();

            if ( result.status === 'success' ) {
                return true;
            }

            NexoAPI.Toast()( result.message );

            this.openPopup().then( result => {
                setTimeout(() => {
                    if ( action === 'payment' ) {
                        PayBoxController[ 'prototype' ].scope.openPayBox();
                    } else {
                        v2Checkout.angular.saveBox.openSaveBox();
                    }
                }, 500 );
            })

            return false;
        },  
        openPopup() {
            return new Promise( ( resolve, reject ) => {
                NexoAPI.Bootbox().confirm( `
                    <div id="tailor-measures">
                        <h3 class="text-center">${this.textDomain.customerMeasures}</h3>
                        <ul class="nav nav-tabs tab-grid">
                            <li @click="selectedTab = 'shirt'" :class="{ 'active' : selectedTab === 'shirt' }"><a href="javascript:void(0)">${this.textDomain.shirt}</a></li>
                            <li @click="selectedTab = 'pant'" :class="{ 'active' : selectedTab === 'pant' }"><a href="javascript:void(0)">${this.textDomain.pant}</a></li>
                            <li @click="selectedTab = 'delivery'" :class="{ 'active' : selectedTab === 'delivery' }"><a href="javascript:void(0)">${this.textDomain.delivery}</a></li>
                        </ul>
                        <div style="padding: 10px" v-if="selectedTab === 'pant'">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ textDomain.style }}</label>
                                        <input class="form-control" v-model="tailor.pant.style">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ textDomain.aroundWaist }}</label>
                                        <input class="form-control" v-model="tailor.pant.around_waist">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ textDomain.aroundHip }}</label>
                                        <input class="form-control" v-model="tailor.pant.around_hip">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ textDomain.frontRise }}</label>
                                        <input class="form-control" v-model="tailor.pant.front_rise">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ textDomain.aroundThigh }}</label>
                                        <input class="form-control" v-model="tailor.pant.around_thigh">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ textDomain.length }}</label>
                                        <input class="form-control" v-model="tailor.pant.length">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ textDomain.inseam }}</label>
                                        <input class="form-control" v-model="tailor.pant.inseam">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ textDomain.aroundKnee }}</label>
                                        <input class="form-control" v-model="tailor.pant.around_knee">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ textDomain.legOpening }}</label>
                                        <input class="form-control" v-model="tailor.pant.leg_opening">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="padding: 10px" v-if="selectedTab === 'shirt'">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ textDomain.style }}</label>
                                        <input class="form-control" v-model="tailor.shirt.style">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ textDomain.aroundNeck }}</label>
                                        <input class="form-control" v-model="tailor.shirt.around_neck">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ textDomain.neckShoulderLength }}</label>
                                        <input class="form-control" v-model="tailor.shirt.neck_shoulder_length">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ textDomain.shoulderShoulder }}</label>
                                        <input class="form-control" v-model="tailor.shirt.shoulder_shoulder">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ textDomain.aroundChest }}</label>
                                        <input class="form-control" v-model="tailor.shirt.around_chest">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ textDomain.sleeveLength }}</label>
                                        <input class="form-control" v-model="tailor.shirt.sleeve_length">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>{{ textDomain.sleeveCuff }}</label>
                                        <input class="form-control" v-model="tailor.shirt.sleeve_cuff">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ textDomain.aroundWaist }}</label>
                                        <input class="form-control" v-model="tailor.shirt.around_waist">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ textDomain.frontLength }}</label>
                                        <input class="form-control" v-model="tailor.shirt.front_length">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ textDomain.backLength }}</label>
                                        <input class="form-control" v-model="tailor.shirt.back_length">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ textDomain.measureHip }}</label>
                                        <input class="form-control" v-model="tailor.shirt.measure_hip">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ textDomain.measureShirtHem }}</label>
                                        <input class="form-control" v-model="tailor.shirt.measure_shirt_hem">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="padding: 10px" v-if="selectedTab === 'delivery'">
                            <div class="form-group">
                                <label>{{ textDomain.priority }}</label>
                                <select class="form-control" v-model="tailor.priority">
                                    <option value="normal">{{ textDomain.normal }}</option>
                                    <option value="medium">{{ textDomain.medium }}</option>
                                    <option value="high">{{ textDomain.high }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ textDomain.assignedTailor }}</label>
                                <select class="form-control" v-model="tailor.assigned_tailor">
                                    <option v-for="tailor of tailors" :value="tailor.user_id">{{ tailor.user_name }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ textDomain.deliveryDate }}</label>
                                <input class="form-control" v-model="tailor.delivery" type="date">
                            </div>
                            <div class="form-group">
                                <label>{{ textDomain.expirationDate }}</label>
                                <input class="form-control" v-model="tailor.expiration_date" type="date">
                            </div>
                            <div class="form-group">
                                <label>{{ textDomain.assignedImage }}</label>
                                <input class="form-control image-assigned" v-model="image" type="file">
                            </div>
                        </div>
                    </div>
                `, ( action ) => {
                    if ( action ) {
                        v2Checkout.CartMetas[ 'tailor' ]    =   tailorModule.tailor;
                        resolve( true );
                    }
                });

                $( '.bootbox-confirm' ).css({
                    'display': 'flex',
                    'align-items': 'center'
                })

                if ( [ 'sm', 'xs' ].includes( layout.is() ) ) {
                    $( '.modal-dialog' ).css({
                        width: '95%'
                    })
                }

                let tailorModule =   new Vue({
                    el: '#tailor-measures',
                    data : {
                        ...TailorAppData,
                        selectedTab : 'shirt',
                        tailor  :   {
                            shirt: {},
                            pant: {},
                        },
                        image: '',
                    },
                    watch: {
                        image() {
                            if ( this.image !== '' ) {
                                console.log( 'here' );
                                this.toBase64( document.querySelector( '.image-assigned' ).files[0] ).then( result => {
                                    this.tailor.assigned_image = result;
                                    console.log( this.tailor );
                                }).catch( error => {
                                    console.log( error );
                                })
                            }
                        }
                    },
                    methods: {
                        toBase64( file ) {
                            return new Promise( ( resolve, reject ) => {
                                const reader    =   new FileReader();
                                reader.readAsDataURL( file );
                                reader.onload   =   _ => resolve( reader.result );
                                reader.onerror  =   _ => reject(_);
                            });
                        },
                    },
                    mounted() {
                        console.log( v2Checkout.CartMetas[ 'tailor' ] );
                        this.tailor     =   v2Checkout.CartMetas[ 'tailor' ] || {
                            shirt: {},
                            pant: {}
                        };
                    }
                });
            })
        },

        isMeasuresDefined() {
            const tailor    =   v2Checkout.CartMetas[ 'tailor' ];

            console.log( tailor );

            if ( tailor === undefined ) {
                return {
                    status: 'failed',
                    message: this.textDomain.noMeasureDefined
                }
            }

            if ( tailor[ 'delivery' ] === undefined ) {
                return {
                    status: 'failed',
                    message: this.textDomain.missingDeliveryDate
                }
            }

            if ( tailor[ 'assigned_tailor' ] === undefined ) {
                return {
                    status: 'failed',
                    message: this.textDomain.missingTailor
                }
            }

            return {
                status : 'success',
                message : this.textDomain.measureDefined
            }
        }
    }
})
</script>