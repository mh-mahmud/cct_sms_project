<!-- Group Modal -->
<template>
    <div id="change-value-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
                <h3>Changed Value</h3>
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
                                                <span>{{ modalData.date_time | formatDate('MM/DD/YYYY hh:mm A') }}</span>
                                            </div>
                                            <div class="m-widget28__tab-item">
                                                <span>User Name</span> 
                                                <span>{{ modalData.user_name }}</span>
                                            </div>
                                            <div class="m-widget28__tab-item">
                                                <span>IP</span>
                                                <span>{{ modalData.ip }}</span>
                                            </div>
                                            <div class="m-widget28__tab-item">
                                                <span>Change Type</span> 
                                                <span class="">{{ changeType[modalData.changed_type] }}</span>
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
                <table class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline collapsed" id="m_table_1" role="grid" aria-describedby="m_table_1_info">
                    <thead>
                        <tr>
                            <td><b>Field</b></td>
                            <td><b>Value</b></td>
                        </tr>
                    </thead>
                    <tbody>                        
                        <tr v-for="(log, index) in modalData.changed_value">
                            <td><b v-if="index!='password'">{{ index }}</b></td>
                            <td v-if="index!='password'">{{ log }}</td>
                        </tr>
                    </tbody>
                </table>
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
            colorClass:[],
            changeType:[]
        }
    },
    mounted(){
       this.baseUrl = BASE_URL;
       this.colorClass['A'] = 'bg-light';this.colorClass['U'] = 'bg-success';this.colorClass['D'] = 'bg-danger';
       this.changeType['A'] = 'ADD';this.changeType['U'] = 'UPDATE';this.changeType['D'] = 'DELETE';this.changeType['L'] = 'LOGIN';this.changeType['O'] = 'LOGOUT';
       this.closeModal("#change-value-modal");
       this.showModal("#change-value-modal");
    },
    props:['modalData'],
    methods: {
        getColor(modalData){
            return (this.colorClass[modalData.changed_type]) ? this.colorClass[modalData.changed_type] : this.colorClass['A'];
        },
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