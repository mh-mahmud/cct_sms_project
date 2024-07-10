<template>
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <js-plugin :js-plugin="data.js_plugin"></js-plugin>
        <!-- BreadCrumb	-->
        <breadcrumb :breadcrumb-data="data.breadcrumb"></breadcrumb>	        
        <div class="m-content">
            <div class="m-portlet m-portlet--mobile">
                <!-- <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text"> 
                                Text List
                            </h3>
                        </div>
                    </div>
                </div> -->
                <div class="m-portlet__body">
                    <div class="m-accordion vue-accordion">
                        <!--begin::Item--> 
                        <div class="m-accordion__item m-accordion__item--success">
                            <div v-b-toggle.collapse1 class="m-accordion__item-head">
                                <span class="m-accordion__item-icon"><i class="fa flaticon-search-1"></i></span>
                                <span class="m-accordion__item-title">Search</span>
                                <span class="m-accordion__item-mode"></span>    
                            </div>
                            <b-collapse id="collapse1" class="vue-accordion-body">
                                
                                <b-card>
                                    <!-- search form -->
                                    <div class="m-form m-form--fit">
                                        <div class="row">
                                            <div class="col-lg-4 m--margin-bottom-10-tablet-and-mobile">
                                                <label>Start Range:</label>
                                                <div class="input-daterange form-group input-group" :class="errors.has('start_time') || errors.has('end_time') ? 'has-error' : ''" id="m_datepicker">
                                                    <date-picker data-vv-as="Start Time"  name="start_time" v-validate="'date_format:MM/DD/YYYY HH:mm'" v-model="searchKey.start_time" 
                                                    :config="datepickerOpt" class="form-control m-input date-time-picker" placeholder="From" autocomplete="off"></date-picker>
                                                    <span class="m-form__help" v-if="errors.has('start_time')">
                                                        {{ errors.first('start_time')}}
                                                    </span>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                                                    </div> 
                                                <date-picker @dp-change="showTimeFormat" data-vv-as="End Time" name="end_time" v-validate="'date_format:MM/DD/YYYY HH:mm|after:'+searchKey.start_time+'|date_between:'+searchKey.start_time+','+getValidDiffDate(searchKey.start_time,15)"  v-model="searchKey.end_time" 
                                                :config="datepickerOpt" class="form-control m-input date-time-picker" placeholder="To" autocomplete="off"></date-picker>  
                                                    <span class="m-form__help" v-if="errors.has('end_time')">
                                                        {{ errors.first('end_time')}}
                                                    </span>
                                                </div>
                                            </div>
                                            <!-- <div class="col-lg-4 m--margin-bottom-10-tablet-and-mobile">
                                                <label>DID:</label>
                                                <input type="text" class="form-control m-input" v-model="searchKey.did" placeholder="DID"/>
                                            </div>    -->
                                            <div class="col-lg-4 m--margin-bottom-10-tablet-and-mobile">
                                                <label>Client Name:</label>
                                                <input type="text" class="form-control m-input" v-model="searchKey.client_name" placeholder="Client Name"/>
                                            </div> 
                                            <div class="col-lg-4 m--margin-bottom-10-tablet-and-mobile">
                                                <label>Client Number:</label>
                                                <input type="text" class="form-control m-input" v-model="searchKey.client" placeholder="Client Number"/>
                                            </div>
                                        </div>
                                        <div class="mt-4"></div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <button class="btn btn-brand m-btn m-btn--icon"  @click="makeSearchQueryStr(),fetchTextList()" id="m_search">
                                                    <span>
                                                        <i class="la la-search"></i>
                                                        <span>Search</span>
                                                    </span>
                                                </button>
                                                &nbsp;&nbsp;
                                                <button class="btn btn-secondary m-btn m-btn--icon" @click="fetchTextList(BASE_URL+'admin-api/outbound-list?page=1&start_time='+searchKey.start_time+'&end_time='+searchKey.end_time+'&per_page='+pagination.per_page),resetsearchKey()" id="m_reset">
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
                                <table class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline collapsed sortable" id="m_table_1" role="grid" aria-describedby="m_table_1_info" style="width: 1044px;">
                                    <thead>
                                        <tr role="row" style="text-align: center">                                                                                      
                                            <th>Ser.</th>
                                            <th>Time <span>*</span></th>                                            
                                            <th>DID</th>
                                            <th>Client Name</th>
                                            <th>Client Number</th>                                            
                                            <th>Direction</th>
                                            <th>Message</th>
                                            <th colspan="2">Action </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(log, index) in data.data" style="text-align: center">                                            
                                            <td>{{index+1}}</td>
                                            <td>{{ log.log_time | formatDate('MM/DD/YYYY hh:mm A') }} </td>                                            
                                            <td>{{ log.did | formatPhone }}</td>
                                            <td>{{ log.first_name }}</td>
                                            <td>{{ log.client_number | formatPhone }}</td>
                                            <td><span class="badge">{{ data.directions[log.direction]}}</span></td>
                                            <td>{{ log.sms_text | formatText }}</td>
                                            <td>
                                                <a href="#" class="btn btn-info btn-reply" @click.prevent="bindModalData(log)" data-toggle="modal" data-target="#text-modal" title="Read">
                                                    Read
                                                </a>
                                                <router-link href="#"  v-bind:to="{name: 'InboxList', params:{from: log.did, to: log.client_number}}" class="btn btn-primary btn-reply" data-toggle="m-tooltip" title="Reply">
                                                    Reply
                                                </router-link>
                                                    
                                            </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-1">
                                <div role="status" aria-live="polite" class="dataTables_info">
                                    <select name="count" class="form-control m-input" @change="fetchTextList()"  v-model="pagination.per_page" style="padding: 0.65rem 0.4rem">
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="dataTables_info" id="m_table_1_info" role="status" aria-live="polite">Showing {{pagination.from}} to {{pagination.to}} of {{pagination.total}} entries</div>
                            </div>
                            <div class="col-sm-12 col-md-7 dataTables_pager">
                                
                                <div class="dataTables_paginate paging_simple_numbers" id="m_table_1_paginate">
                                    <vue-pagination  :pagination="pagination" @paginate="fetchTextList()" :offset="4"> </vue-pagination>
                                </div>
                            </div>                            
                            
                        </div>
                        <div class="row" style="line-height: 3">
                            <div class="col-sm-12 col-md-5">
                                <span><i>* Time Zone: {{data.userTimeZone}}</i></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->		        
            <!-- template modal -->
            <text-modal v-bind:modal-data="modalData"></text-modal>
        </div>
    </div>
</template>

<script>
import AppComponent from '../../components/AppComponent'
import TextModal from './outbound_text_modal'
export default {
  extends: AppComponent,
  components:{
      TextModal
    },
    data() {
        return {
            datepickerOpt:{format: 'MM/DD/Y HH:mm',useCurrent: 'day',showClear: true,showClose: true},
            searchKey: {                
                'start_time': '',
                'end_time': '',
                'did': '',
                'client': '',
                'client_name': '',
            },
            modalData: {},
            searchQueryStr: '',
            data: {},
            pagination: {},
            sortKey: 'log_time',
            reverse: false,
        }
    },
    mounted() { 
        this.fetchTextList();
        this.bindCurrentRoute();
    },
    methods: {
        showTimeFormat(){
            this.searchKey.end_time = moment(this.searchKey.end_time).format('MM/DD/Y')+" 23:59";                            
        },
        Reload(){
            this.fetchTextList();
        },        
        resetsearchKey(){
            this.searchKey = {
                // 'start_time': moment().format('MM/DD/Y')+" 00:00",
                // 'end_time': moment().format('MM/DD/Y')+" 23:59",
                'start_time': '',
                'end_time': '',
                'client': '',
                'did': '',
                'client_name': '',
            }            
        },
        makeSearchQueryStr(){ 
            this.pagination.current_page = 1;
            this.bindSearchQueryStr();
        },
        bindSearchQueryStr(){ 
            var queryStr = "";

            // console.log(this.searchKey);
            jQuery.each(this.searchKey, function(key, value) { 
                if(key=='start_time' || key=='end_time'){  
                    if(value != null && moment(value, 'MM/DD/YYYY HH:mm',true).isValid()){
                        queryStr += "&"+key+"="+moment(value, 'MM/DD/YYYY HH:mm').format('YYYY-MM-DD HH:mm');
                    }
                    
                }else if(value.length > 0){
                    queryStr += "&"+key+"="+value;
                }            
            });
            this.searchQueryStr = queryStr;
        },
        getValidDiffDate(stDate, dateDiff){
            var getStDate = moment(String(stDate), 'MM/DD/YYYY HH:mm').format('YYYY-MM-DD HH:mm');
            var formatStDate = new Date(getStDate);
            var dateDiffObj = moment(formatStDate).add(dateDiff, 'days');
            var dateDiff = moment(String(dateDiffObj)).format('MM/DD/YYYY HH:mm');
            return dateDiff;
        },
        // bind data to use on modal
        bindModalData(data){
            this.modalData = data;
        },
        // Fetch List
        fetchTextList(page_url) {
            this.$validator.validateAll().then((result) => {
                if(result == true){
                    page_url = page_url || BASE_URL+'admin-api/outbound-list?page='+this.pagination.current_page+'&per_page='+this.pagination.per_page+this.searchQueryStr;
                    this.getPagiData(page_url);
                }
            }); 
        },
    },

}
</script>
