<template>
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <js-plugin :js-plugin="data.js_plugin"></js-plugin>
        <!-- BreadCrumb	-->
        <breadcrumb :breadcrumb-data="data.breadcrumb"></breadcrumb>
        <div class="m-content">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text"> 
                                Campaign Profile List 
                            </h3>
                        </div>
                    </div>
                    
                </div>
                <div class="m-portlet__body">
                    <div class="m-accordion vue-accordion">    
                        <!--begin::Item--> 
                        <div class="m-accordion__item m-accordion__item--success">
                            <div v-b-toggle.collapse1 class="m-accordion__item-head">
                                <span class="m-accordion__item-icon"><i class="fa flaticon-search-1"></i></span>
                                <span class="m-accordion__item-title">Campaign Profile</span>
                                <span class="m-accordion__item-mode"></span>    
                            </div>

                            <b-collapse id="collapse1" class="vue-accordion-body">
                                <b-card>
                                    <!-- search form -->
                                    <div class="m-form m-form--fit">
                                        <div class="row">
                                            <div class="col-lg-5 m--margin-bottom-10-tablet-and-mobile">
                                                <label>Campaign Date Range:</label>
                                                <div class="input-daterange form-group input-group" :class="errors.has('start_time') || errors.has('end_time') ? 'has-error' : ''" id="m_datepicker">
                                                    <date-picker data-vv-as="Start Time"  name="start_time" v-validate="'date_format:DD/MM/YYYY HH:mm'" v-model="searchKey.start_time" 
                                                    :config="datepickerOpt" class="form-control m-input date-time-picker" placeholder="From" autocomplete="off"></date-picker>
                                                    <span class="m-form__help" v-if="errors.has('start_time')">
                                                        {{ errors.first('start_time')}}
                                                    </span>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                                                    </div>
                                                <date-picker data-vv-as="End Time" name="end_time" v-validate="'date_format:DD/MM/YYYY HH:mm|after:'+searchKey.start_time"  v-model="searchKey.end_time" 
                                                :config="datepickerOpt" class="form-control m-input date-time-picker" placeholder="To" autocomplete="off"></date-picker>  
                                                    <span class="m-form__help" v-if="errors.has('end_time')">
                                                        {{ errors.first('end_time')}}
                                                    </span>
                                                </div>
                                            </div>                                            
                                           
                                            <div class="col-lg-3 m--margin-bottom-10-tablet-and-mobile">
                                                <label>Title:</label>
                                                <input type="text" class="form-control m-input" v-model="searchKey.title" placeholder="Title"/>
                                            </div>
                                            <div class="col-lg-3 m--margin-bottom-10-tablet-and-mobile">
                                                <label>Status:</label>
                                                <select id="status" class="form-control select2 select2-status" v-model="searchKey.status" >
                                                    <option value="">All</option>
                                                    <option v-for="(val, id) in data.campStatus" :value="id" :key="id">{{val}}</option>
                                                </select>
                                            </div>
                                            
                                        </div>
                                        <div class="mt-4"></div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <button class="btn btn-brand m-btn m-btn--icon"  @click="makeSearchQueryStr(),fetchCampaigns()" id="m_search">
                                                    <span>
                                                        <i class="la la-search"></i>
                                                        <span>Search</span>
                                                    </span>
                                                </button>
                                                &nbsp;&nbsp;
                                                <button class="btn btn-secondary m-btn m-btn--icon" @click="fetchCampaigns('api/campaign-list?page=1'), resetsearchKey()" id="m_reset">
                                                    <span>
                                                        <i class="la la-close"></i>
                                                        <span>Reset</span>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- search form -->
                                </b-card>
                            </b-collapse>
                                
                        </div>   
                        <!--end::Item--> 
                    </div> 
                    <!--begin: Datatable -->
                    <div id="m_table_1_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline collapsed" id="m_table_1" role="grid" aria-describedby="m_table_1_info" style="width: 1044px;">
                                    <thead>
                                        <tr role="row">
                                            <th>Title</th>
                                            <th>Campaign Tag</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Upload Leads</th>
                                            <th>Total</th>
                                            <th>Invalid</th>
                                            <th>Send</th>
                                            <th>Status</th>
                                            <th colspan="2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(campaign, index) in data.data" v-bind:key="campaign.id">
                                            <td>
                                                <router-link href="#"  v-bind:to="{name: 'emailList', params: {id:campaign.id}}" class="text-info" data-toggle="m-tooltip"  title="Email List">
                                                    {{campaign.title}}
                                                </router-link>
                                            </td>
                                            <td>{{campaign.tag}}</td>
                                            <td>{{campaign.start_time | formatDate('DD MMM YYYY hh:mm A')}}</td>
                                            <td>{{campaign.end_time | formatDate('DD MMM YYYY hh:mm A')}}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-info" v-on:click="bindleadsData(campaign)" data-toggle="modal" data-target="#upload-leads"> 
                                                    <i class="fa fa-upload"></i> Upload
                                                </button>
                                            </td>
                                            <td>{{campaign.valid_emails}}</td>
                                            <td>{{campaign.invalid_emails}}</td>
                                            <td>{{campaign.send}}</td>
                                            <td>{{data.campStatus[campaign.status]}}</td>
                                            <td>
                                                <button v-if="campaign.status !='D'" type="button" class="btn btn-sm btn-success" v-on:click="updateCampStatus(index, campaign.id,campaign.status);"> 
                                                    <span>{{campaign.status == "P" ? "Stop" : "Start"}}</span>
                                                </button>
                                                <router-link href="#"  v-bind:to="{name: 'CampaignProfileDetail', params: {id:campaign.id}}" class="text-info" data-toggle="m-tooltip"  title="Detail">
                                                    <i class='fa fa-folder'></i>
                                                </router-link>
                                                <router-link href="#"  v-bind:to="{name: 'CampaignProfileEdit', params: {id:campaign.id}}" class="text-info" data-toggle="m-tooltip"  title="Edit">
                                                    <i class='fa fa-edit'></i>
                                                </router-link>
                                            
                                                <a @click.prevent="deleteCampaign(campaign.id,index)"  class="text-danger" href="#" data-toggle="m-tooltip" title="Delete">
                                                    <i class='fa fa-trash'></i>
                                                </a>
                                                    
                                            </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="m_table_1_info" role="status" aria-live="polite">Showing {{pagination.from}} to {{pagination.to}} of {{pagination.total}} entries</div>
                            </div>
                            <div class="col-sm-12 col-md-7 dataTables_pager">
                                <div class="dataTables_paginate paging_simple_numbers" id="m_table_1_paginate">
                                    <vue-pagination  :pagination="pagination" @paginate="fetchCampaigns()" :offset="4"> </vue-pagination>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- leads upload modal -->
                    <campaign-leads-upload v-bind:modal-data="modalData"></campaign-leads-upload>

                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->		        
        </div>
    </div>
</template>

<script>
import AppComponent from '../../../components/AppComponent'
import CampaignLeadsUpload from './leads'
export default {
    extends: AppComponent,
    components:{
        CampaignLeadsUpload
    },
    data() {
        return {
            datepickerOpt:{format: 'DD/MM/Y HH:mm',useCurrent: false,showClear: true,showClose: true},
            data: {},
            pagination: {},
            modalData: {},
            searchKey: {
                'start_time': '',
                'end_time': '',
                'status': '',
                'title': '',
            },
            searchQueryStr:""
        }
    },
    mounted() { 
        this.fetchCampaigns();
        this.bindCurrentRoute();
        this.makeSearchQueryStr();
    },
    methods: {
        // Fetch Campaigns List
        fetchCampaigns(page_url) {
            this.$validator.validateAll().then((result) => {
                if(result == true){
                    page_url = page_url || 'api/campaign-list?page='+this.pagination.current_page+this.searchQueryStr;
                    this.getPagiData(page_url);
                }

            }); 
            
        },

        deleteCampaign(id, index){
            var self = this;
            this.$deletePagiItem(self.data.data, index, self.pagination, 'Do you really want to delete this campaign?', 'api/campaign/' + id);
        },
        bindleadsData(campaign){
            this.modalData = campaign;
        },
        resendCampEmail(campId){
            let vm = this;
            commonLib.blockUI({target: ".m-content",animate: true,overlayColor: 'none'});
            axios.get("api/resend-campaign-email/"+campId).then((res) => 
            { 
                this.fetchCampaigns();
                commonLib.iniToastrNotification(res.data.response_msg.type, res.data.response_msg.title, res.data.response_msg.message);
                commonLib.unblockUI(".m-content");
            })
            .catch(function (error) {
                console.log(error.response);
                commonLib.unblockUI(".m-content");
            });
        },
        updateCampStatus(index, campId, currentStatus){ 
            var msg = "Are you sure to change status?"
            let self = this;
            bootbox.confirm(msg, function (result) {
                if (result == true) {
                    var status = "I";
                    if(currentStatus == "I" || currentStatus == "S"){
                        status = "P";
                    }
                    else if(currentStatus == "P"){
                        status = "S";
                    }

                    commonLib.blockUI({target: ".m-content",animate: true,overlayColor: 'none'});
                    axios.get("api/update-camp-status/"+campId+"/"+status).then((res) => 
                    { 
                        self.data.data[index].status = status;
                        commonLib.iniToastrNotification(res.data.response_msg.type, res.data.response_msg.title, res.data.response_msg.message);
                        commonLib.unblockUI(".m-content");
                    })
                    .catch(function (error) {
                        console.log(error.response);
                        commonLib.unblockUI(".m-content");
                    });
                }
            });
            
        },
        makeSearchQueryStr(){ 
            this.pagination.current_page = 1;
            this.bindSearchQueryStr();
        },
        bindSearchQueryStr(){ 
            var queryStr = "";
            this.searchKey.status = $('#status').val();

            jQuery.each(this.searchKey, function(key, value) { 
                if(key=='start_time' || key=='end_time'){  
                    if(value != null && moment(value, 'DD/MM/YYYY HH:mm',true).isValid()){
                        queryStr += "&"+key+"="+moment(value, 'DD/MM/YYYY HH:mm').format('YYYY-MM-DD HH:mm');
                    }
                    
                }else if(value.length > 0){
                    queryStr += "&"+key+"="+value;
                }            
            });
            // console.log(queryStr);
            this.searchQueryStr = queryStr;
        },
        resetsearchKey(){
            this.searchKey = {
                'start_time': '',
                'end_time': '',
                'status': '',
                'title':''
            };
            $('#status').val('').trigger("change");
            this.searchQueryStr = "";
        },
       
    },

}
</script>
