<!-- Text Modal -->
<template>
    <div id="text-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
                <h3>Message</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>                                
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4">
                <div class="m-portlet m-portlet--mobile">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">Details</h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="m-widget28">
                            <div class="m-widget28__container">
                                <div class="m-widget28__tab tab-content" style="margin-top: 0rem">
                                    <div class="m-widget28__tab-container tab-pane active">
                                        <div class="m-widget28__tab-items">
                                            <div class="m-widget28__tab-item">
                                                <span>Time</span> 
                                                <span>{{ modalData.log_time | formatDate('MM/DD/YYYY hh:mm A') }}</span>
                                            </div>
                                            <div class="m-widget28__tab-item">
                                                <span>DID</span> 
                                                <span>{{ modalData.did }}</span>
                                            </div>
                                            <div class="m-widget28__tab-item">
                                                <span>Client Number</span>
                                                <span>{{ modalData.client_number }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <p style="word-break: break-all">
                    {{ modalData.sms_text }}
                </p>
            </div>
        </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </div>

        </div>
    </div>
</template>
<script>
import AppComponent from '../../components/AppComponent'
export default {
    extends: AppComponent,
    data() {
        return {
        }
    },
    mounted(){
       this.baseUrl = BASE_URL;
       this.closeModal("#text-modal");
       this.showModal("#text-modal");
    },
    props:['modalData'],
    methods: {
        closeModal(refid){
            var self = this;
            $(refid).on('hidden.bs.modal', function () { 
                
            });
        },
        showModal(refid){
            var self = this;
            $(refid).on('shown.bs.modal', function () { 
              
            });
        }
    }
}
</script>