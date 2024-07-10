<template>
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <js-plugin :js-plugin="data.js_plugin"></js-plugin>
        <!-- BreadCrumb	-->
        <breadcrumb :breadcrumb-data="data.breadcrumb"></breadcrumb>
        <div class="m-content">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title col-md-12">
                            <h3 class="m-portlet__head-text"> 
                                Campaign Email List <span v-if="data.campaignDetail"><i class="fa fa-angle-right"></i> {{data.campaignDetail.title}}</span>
                            </h3>
                            <a v-if="data.data" :href="baseUrl+'api/campaign/export-leads-email/'+$route.params.id" class="btn btn-accent m-btn m-btn--icon mt-2 pull-right">
                                <span>
                                    <i class="la la-download"></i>
                                    <span>Export</span>
                                </span>
                            </a>
                        </div>
                    </div>
                    
                </div>
                <div class="m-portlet__body">
                    <div class="m-accordion vue-accordion">    
                        <!--begin::Item--> 
                        <div class="m-accordion__item m-accordion__item--success">
                            <div v-b-toggle.collapse1 class="m-accordion__item-head">
                                <span class="m-accordion__item-icon"><i class="fa flaticon-search-1"></i></span>
                                <span class="m-accordion__item-title">Campaign Email</span>
                                <span class="m-accordion__item-mode"></span>    
                            </div>

                            <b-collapse id="collapse1" class="vue-accordion-body">
                                <b-card>
                                    <!-- search form -->
                                    <div class="m-form m-form--fit">
                                        <div class="row">                                           
                                           
                                            <div class="col-lg-5 m--margin-bottom-10-tablet-and-mobile">
                                                <label>Email:</label>
                                                <input type="text" class="form-control m-input" v-model="searchKey.email" placeholder="Email"/>
                                            </div>
                                            <div class="col-lg-4 m--margin-bottom-10-tablet-and-mobile">
                                                <label>Status:</label>
                                                <select id="status" class="form-control select2 select2-status" v-model="searchKey.status" >
                                                    <option value="">All</option>
                                                    <option v-for="(val, id) in data.leadStatus" :value="id" :key="id">{{val}}</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-3 m--margin-bottom-10-tablet-and-mobile" style="margin-top:25px;">
                                                <button class="btn btn-brand m-btn m-btn--icon"  @click="makeSearchQueryStr(),fetchCampEmailList()" id="m_search">
                                                    <span>
                                                        <i class="la la-search"></i>
                                                        <span>Search</span>
                                                    </span>
                                                </button>
                                                &nbsp;&nbsp;
                                                <button class="btn btn-secondary m-btn m-btn--icon" @click="fetchCampEmailList('api/campaign/email-list/'+$route.params.id+'?page=1'), resetsearchKey()" id="m_reset">
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
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th colspan="2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in data.data" v-bind:key="item.id">
                                            <td>{{item.name}}</td>
                                            <td>{{item.email}}</td>
                                            <td>{{data.leadStatus[item.status]}}</td>
                                            <td>
                                                <a @click.prevent="deleteEmail(item.campaign_id, item.email , index)"  class="text-danger" href="#" data-toggle="m-tooltip" title="Delete">
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
                                    <vue-pagination  :pagination="pagination" @paginate="fetchCampEmailList()" :offset="4"> </vue-pagination>
                                </div>
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
import AppComponent from '../../../components/AppComponent'
export default {
    extends: AppComponent,
    components:{
    },
    data() {
        return {
            data: {},
            pagination: {},
            searchKey: {
                'status': '',
                'email': '',
            },
            searchQueryStr:"",
            baseUrl: BASE_URL
        }
    },
    mounted() { 
        this.fetchCampEmailList();
        this.bindCurrentRoute();
        this.makeSearchQueryStr();
    },
    methods: {
        // Fetch Campaign Email List
        fetchCampEmailList(page_url){
            page_url = page_url || 'api/campaign/email-list/'+this.$route.params.id+'?page='+this.pagination.current_page+this.searchQueryStr;
            this.getPagiData(page_url);
        },

        deleteEmail(campId, email, index){
            var self = this;
            this.$deletePagiItem(self.data.data, index, self.pagination, 'Do you really want to delete this email?', 'api/campaign/delete-email/'+campId+'/'+email);
        },
        makeSearchQueryStr(){ 
            this.pagination.current_page = 1;
            this.bindSearchQueryStr();
        },
        bindSearchQueryStr(){ 
            var queryStr = "";
            this.searchKey.status = $('#status').val();

            jQuery.each(this.searchKey, function(key, value) { 
                if(value.length > 0){
                    queryStr += "&"+key+"="+value;
                }            
            });
            // console.log(queryStr);
            this.searchQueryStr = queryStr;
        },
        resetsearchKey(){
            this.searchKey = {
                'status': '',
                'email':''
            };
            $('#status').val('').trigger("change");
            this.searchQueryStr = "";
        }
       
    }

   

}
</script>
