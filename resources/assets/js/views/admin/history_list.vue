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
                                Schedule List
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
                                <span class="m-accordion__item-title">Search</span>
                                <span class="m-accordion__item-mode"></span>    
                            </div>
                            <b-collapse id="collapse1" class="vue-accordion-body">
                                
                                <b-card>
                                    <!-- search form -->
                                    <div class="m-form m-form--fit">
                                        <div class="row">
                                            <div class="col-lg-6 m--margin-bottom-10-tablet-and-mobile">
                                                <label>Start Range:</label>
                                                <div class="input-daterange form-group input-group" :class="errors.has('start_time') || errors.has('end_time') ? 'has-error' : ''" id="m_datepicker">
                                                    <date-picker data-vv-as="Start Time"  name="start_time" v-validate="'date_format:DD/MM/YYYY HH:mm'" v-model="searchKey.start_time" 
                                                    :config="datepickerOpt" class="form-control m-input date-time-picker" placeholder="From" autocomplete="off"></date-picker>
                                                    <span class="m-form__help" v-if="errors.has('start_time')">
                                                        {{ errors.first('start_time')}}
                                                    </span>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                                                    </div> 
                                                <date-picker @dp-change="showTimeFormat" data-vv-as="End Time" name="end_time" v-validate="'date_format:DD/MM/YYYY HH:mm|after:'+searchKey.start_time+'|date_between:'+searchKey.start_time+','+getValidDiffDate(searchKey.start_time,15)"  v-model="searchKey.end_time" 
                                                :config="datepickerOpt" class="form-control m-input date-time-picker" placeholder="To" autocomplete="off"></date-picker>  
                                                    <span class="m-form__help" v-if="errors.has('end_time')">
                                                        {{ errors.first('end_time')}}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 m--margin-bottom-10-tablet-and-mobile">
                                                <label>Status:</label>
                                                <select data-vv-as="Status" name="status" v-model="searchKey.status" class="form-control m-input">
                                                    <option v-for="(item, index) in data.status" :value="index" :key="index">{{item}}</option>
                                                </select>                                                
                                            </div>  
                                        </div>
                                        
                                        <div class="mt-4"></div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <button class="btn btn-brand m-btn m-btn--icon"  @click="makeSearchQueryStr(),fetchSchedule()" id="m_search">
                                                    <span>
                                                        <i class="la la-search"></i>
                                                        <span>Search</span>
                                                    </span>
                                                </button>
                                                &nbsp;&nbsp;
                                                <button class="btn btn-secondary m-btn m-btn--icon" @click="fetchSchedule('api/history?page=1&start_time='+searchKey.start_time+'&end_time='+searchKey.end_time+'&per_page='+pagination.per_page),resetsearchKey()" id="m_reset">
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
                                            <th>Start Time <span>*</span></th>
                                            <th>Stop Time <span>*</span></th>                                            
                                            <th>Message</th>
                                            <th>Selected</th>
                                            <th>Sent</th>                                          
                                            
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(schedule, index) in data.data" v-bind:key="schedule.schedule_id" style="text-align: center">
                                            <td>{{index+1}}</td>
                                            <td>{{ schedule.start_time | formatDate('MM/DD/YYYY hh:mm A') }} </td>
                                            <td>{{ schedule.stop_time | formatDate('MM/DD/YYYY hh:mm A') }} </td>                                                                                        
                                            <td>{{ schedule.sms_text | formatText }}</td>
                                            <td>{{ schedule.num_contacts }}</td>
                                            <td>{{ schedule.num_sms_sent }}</td>
                                            
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- <span v-if="data.data === undefined || data.data == 0">No data</span> -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-1">
                                <div role="status" aria-live="polite" class="dataTables_info">
                                    <select name="count" class="form-control m-input" @change="fetchSchedule()"  v-model="pagination.per_page" style="padding: 0.65rem 0.4rem">
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
                                    <vue-pagination  :pagination="pagination" @paginate="fetchSchedule()" :offset="4"> </vue-pagination>
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
        </div>
    </div>
</template>

<script>
import AppComponent from '../../components/AppComponent'
import moment from 'moment'
export default {
  extends: AppComponent,
    data() {
        return {
            datepickerOpt:{format: 'DD/MM/Y HH:mm',useCurrent: 'day',showClear: true,showClose: true},
            searchKey: {                
                'start_time': '',
                'end_time': '',
                'status': '',                
            },
            searchQueryStr: '',
            data: {},
            pagination: {},
        }
    },
    mounted() { 
        this.fetchSchedule();
        this.bindCurrentRoute();
    },
    methods: {
        showTimeFormat(){
            this.searchKey.end_time = moment(this.searchKey.end_time).format('MM/DD/Y')+" 23:59";                            
        },
        Reload(){
            this.fetchSchedule();
        },
        resetsearchKey(){
            this.searchKey = {
                'start_time': '',
                'end_time': '',
                'status': '',                
            }            
        },
        makeSearchQueryStr(){ 
            this.pagination.current_page = 1;
            this.bindSearchQueryStr();
        },
        bindSearchQueryStr(){ 
            var queryStr = "";            
            jQuery.each(this.searchKey, function(key, value) { 
                if(key=='start_time' || key=='end_time'){  
                    if(value != null && moment(value, 'DD/MM/YYYY HH:mm',true).isValid()){
                        queryStr += "&"+key+"="+moment(value, 'DD/MM/YYYY HH:mm').format('YYYY-MM-DD HH:mm');
                    }
                    
                }else if(value.length > 0){
                    queryStr += "&"+key+"="+value;
                }            
            });
            this.searchQueryStr = queryStr;
        },
        getValidDiffDate(stDate, dateDiff){
            var getStDate = moment(String(stDate), 'DD/MM/YYYY HH:mm').format('YYYY-MM-DD HH:mm');
            var formatStDate = new Date(getStDate);
            var dateDiffObj = moment(formatStDate).add(dateDiff, 'days');
            var dateDiff = moment(String(dateDiffObj)).format('DD/MM/YYYY HH:mm');
            return dateDiff;
        },
        getHumanDate : function (date) {
                return moment(date, 'YYYY-MM-DD hh:mm').format('DD-MM-YYYY hh:mm A');
            },
        // Fetch List
        fetchSchedule(page_url) {
            this.$validator.validateAll().then((result) => {
                if(result == true){
                    page_url = page_url || BASE_URL+'admin-api/history?page='+this.pagination.current_page+'&per_page='+this.pagination.per_page+this.searchQueryStr;
                    this.getPagiData(page_url);
                }
            });
        },

        deleteSchedule(id, index){
            var self = this;
            this.$deletePagiItem(self.data.data, index, self.pagination, 'Are you sure you want to delete this schedule?', BASE_URL+'admin-api/schedules/' + id);
        }
    },

}
</script>
