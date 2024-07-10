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
                                Contact List
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
                                            <div class="col-lg-3 m--margin-bottom-10-tablet-and-mobile">
                                                <label>Phone:</label>
                                                <input type="text" class="form-control m-input" v-model="searchKey.phone" placeholder="Phone"/>
                                            </div>
                                            <div class="col-lg-3 m--margin-bottom-10-tablet-and-mobile">
                                                <label>First Name:</label>
                                                <input type="text" class="form-control m-input" v-model="searchKey.first_name" placeholder="First Name"/>
                                            </div>   
                                            <div class="col-lg-3 m--margin-bottom-10-tablet-and-mobile">
                                                <label>Last Name:</label>
                                                <input type="text" class="form-control m-input" v-model="searchKey.last_name" placeholder="Last Name"/>
                                            </div> 
                                            <div class="col-lg-3 m--margin-bottom-10-tablet-and-mobile">
                                                <label>Company:</label>
                                                <input type="text" class="form-control m-input" v-model="searchKey.company" placeholder="Company"/>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4"></div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <button class="btn btn-brand m-btn m-btn--icon"  @click="makeSearchQueryStr(),fetchContacts()" id="m_search">
                                                    <span>
                                                        <i class="la la-search"></i>
                                                        <span>Search</span>
                                                    </span>
                                                </button>
                                                &nbsp;&nbsp;
                                                <button class="btn btn-secondary m-btn m-btn--icon" @click="fetchContacts(BASE_URL+'admin-api/contact-list?page=1'+'&per_page='+pagination.per_page),resetsearchKey()" id="m_reset">
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

                            <!-- </b-collapse>  -->
                                
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
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Phone</th>
                                            <th>Company</th>
                                            <!-- <th>Type</th>                                            
                                            <th>Country</th> -->
                                            <th colspan="2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(contact, index) in data.data" v-bind:key="contact.id" style="text-align: center">
                                            <td>{{index+1}}</td>
                                            <td>{{ contact.first_name }}</td>
                                            <td>{{ contact.last_name }}</td>
                                            <td>{{ contact.phone | formatPhone}}</td>
                                            <td>{{ contact.company }}</td>
                                            <!-- <td>{{ data.phoneType[contact.phone_type] }}</td>                                            
                                            <td>{{ contact.country }}</td> -->
                                            <td> 
                                                <!-- <router-link href="#"  v-bind:to="{name: 'ContactDetail', params: {id:contact.id}}" class="text-info" data-toggle="m-tooltip" title="Detail">
                                                    <i class='fa fa-folder'></i>
                                                </router-link> -->
                                                <router-link href="#"  v-bind:to="{name: 'AdminContactEdit', params: {id:contact.id}}" class="text-info" data-toggle="m-tooltip" title="Edit">
                                                    <i class='fa fa-edit'></i>
                                                </router-link>
                                            
                                                <a @click.prevent="deleteUser(contact.id,index)"  class="text-danger" href="#" data-toggle="m-tooltip" title="Delete">
                                                    <i class='fa fa-trash'></i>
                                                </a>
                                                    
                                            </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-1">
                                <div role="status" aria-live="polite" class="dataTables_info">
                                    <select name="count" class="form-control m-input" @change="fetchContacts()"  v-model="pagination.per_page" style="padding: 0.65rem 0.4rem">
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
                                    <vue-pagination  :pagination="pagination" @paginate="fetchContacts()" :offset="4"> </vue-pagination>
                                </div>
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
import AppComponent from '../../components/AppComponent'
export default {
  extends: AppComponent,
    data() {
        return {
            searchKey: {                
                'first_name': '',
                'last_name': '',
                'phone': '',
                'company': '',
            },
            searchQueryStr: '',
            data: {},
            pagination: {},
        }
    },
    mounted() { 
        this.fetchContacts();
        this.bindCurrentRoute();
    },
    methods: {
        Reload(){
            this.fetchContacts();
        },
        resetsearchKey(){
            this.searchKey = {
                'first_name': '',
                'last_name': '',
                'phone': '',
                'company': '',
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
                if(value.length > 0){
                    queryStr += "&"+key+"="+value;
                }            
            });
            this.searchQueryStr = queryStr;
        },
        // Fetch List
        fetchContacts(page_url) {
            page_url = page_url || BASE_URL+'admin-api/contact-list?page='+this.pagination.current_page+'&per_page='+this.pagination.per_page+this.searchQueryStr;
            this.getPagiData(page_url);
        },

        deleteUser(id, index){
            var self = this;
            this.$deletePagiItem(self.data.data, index, self.pagination, 'Are you sure you want to delete this list?', BASE_URL+'admin-api/contacts/' + id);
        }
    },

}
</script>
