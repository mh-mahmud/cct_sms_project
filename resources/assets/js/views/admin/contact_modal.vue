<!-- Contact Modal -->
<template>
    <div id="contact-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
                    <h3>Contact List</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>                                
            </div>
            <div class="modal-body">
                 <!-- search form -->
                                    <div class="m-form m-form--fit">
                                        <div class="row">
                                            <div class="col-lg-3 m--margin-bottom-10-tablet-and-mobile">
                                                <label>First Name:</label>
                                                <input type="text" class="form-control m-input" v-model="searchKey.first_name" placeholder="First Name"/>
                                            </div>   
                                            <div class="col-lg-3 m--margin-bottom-10-tablet-and-mobile">
                                                <label>Last Name:</label>
                                                <input type="text" class="form-control m-input" v-model="searchKey.last_name" placeholder="Last Name"/>
                                            </div> 
                                            <div class="col-lg-3 m--margin-bottom-10-tablet-and-mobile">
                                                <label>Phone:</label>
                                                <input type="text" class="form-control m-input" v-model="searchKey.phone" placeholder="Phone"/>
                                            </div>
                                            <div class="col-lg-3 m--margin-bottom-10-tablet-and-mobile">
                                                <label>Company:</label>
                                                <input type="text" class="form-control m-input" v-model="searchKey.company" placeholder="Company"/>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4"></div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <button class="btn btn-brand m-btn m-btn--icon"  @click="makeSearchQueryStr(),fetchContactsList()" id="m_search">
                                                    <span>
                                                        <i class="la la-search"></i>
                                                        <span>Search</span>
                                                    </span>
                                                </button>
                                                &nbsp;&nbsp;
                                                <button class="btn btn-secondary m-btn m-btn--icon" @click="fetchContactsList('api/contact-list-modal-filter/'+GROUPID+'?page=1'),resetsearchKey()" id="m_reset">
                                                    <span>
                                                        <i class="la la-close"></i>
                                                        <span>Reset</span>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                <div id="m_table_1_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- <div class="mr-1"><a href="#" data-dismiss="modal" @click.prevent="insertGroup()" v-bind:class="selected.length ? 'btn m-btn--air btn-accent' : 'btn m-btn--air btn-accent disabled'"><span><i class="la la-plus"></i> <span>Insert {{selected.length}} Groups</span></span></a></div> -->
                            <br />
                            <table class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline collapsed" id="m_table_1" role="grid" aria-describedby="m_table_1_info">
                                    <thead>
                                        <tr role="row" style="text-align: center">
                                            <th><input type="checkbox" v-model="selectAll"></th>                                          
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Phone</th>
                                            <th>Company</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(contact, index) in data.data" v-bind:key="contact.id" style="text-align: center">
                                            <td><input type="checkbox" name="listselect" v-model="selected" :value="contact.id" number></td>
                                            <td>{{ contact.first_name }}</td>
                                            <td>{{ contact.last_name }}</td>
                                            <td>{{ contact.phone | formatPhone}}</td>
                                            <td>{{ contact.company }}</td>
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
                                <vue-pagination  :pagination="pagination" @paginate="fetchContactsList()" :offset="4"> </vue-pagination>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" data-dismiss="modal" @click.prevent="insertContact()" v-bind:class="selected.length ? 'btn m-btn--air btn-accent' : 'btn m-btn--air btn-accent disabled'"><span> <span>Insert {{selectAll ? data.meta.total : selected.length}} Contact</span></span></a>
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
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
           searchKey: {                
                'first_name': '',
                'last_name': '',
                'phone': '',
                'company': '',
           },
           GROUPID: '',
           searchQueryStr: '',
           selected: [],
           data: {},           
           pagination: {},
        }
    },
    mounted(){
       this.baseUrl = BASE_URL;
       if(this.$route.params.group_id){
           this.GROUPID = this.$route.params.group_id;
           this.fetchContactsList();
       }
       this.closeModal("#group-modal");
       this.showModal("#group-modal");
    },
    props:['modalData'],
    computed: {
        selectAll: {
            get: function () {
                return this.data.data ? this.selected.length == this.data.data.length : false;
            },
            set: function (value) { 
                var contacts = this.data.data;  
                if (value && contacts) {   
                    for (var i = 0; i < contacts.length; i++) {  
                        if(this.selected.indexOf(contacts[i].id) === -1) {
                            this.selected.push(contacts[i].id); 
                        }    
                    }
                }else{
                    this.selected = [];
                }                        
            }
        }
    },
    methods: {
        filterData(data){
            if(data.length){ 
                var arr = $.map(data, function(elm, index) {
                    if(this.filter){
                        for (var i = 0; i < this.filter.length; i++){
                            if(this.filter[i].id != elm['id']){
                                return elm;
                            }
                        }
                    }
                });
               return arr;
            }
            return [];
        },
        closeModal(refid){
            var self = this;
            $(refid).on('hidden.bs.modal', function () { 
                self.selected = [];
            });

            
        },
        showModal(refid){
            var self = this;
            $(refid).on('shown.bs.modal', function () { 
              
            });
        },
        resetsearchKey(){
            this.searchKey = {
                'first_name': '',
                'last_name': '',
                'phone': '',
                'company': '',
            }
            this.selected = [];
            this.selectAll = false;  
        },
        makeSearchQueryStr(){ 
            this.pagination.current_page = 1;
            this.bindSearchQueryStr();
        },
        bindSearchQueryStr(){ 
            var queryStr = "";
            jQuery.each(this.searchKey, function(key, value) { 
                if(value.length > 0){
                    queryStr += "&"+key+"="+value;
                }            
            });
            this.searchQueryStr = queryStr;
        },
        fetchContactsList(page_url) {            
            page_url = page_url || BASE_URL+'admin-api/contact-list-modal-filter/'+this.$route.params.group_id+'?page='+this.pagination.current_page+this.searchQueryStr;
            this.getPagiData(page_url);
            this.selected = [];
            this.selectAll = false;  
        },
        insertContact (){
            if(this.selectAll){
                let url = BASE_URL+'admin-api/contact-list-modal-filter-selectall/'+this.$route.params.group_id+'?'+this.searchQueryStr;
                this.$parent.addAllGroupTag(url);                
                return;
            }
            if(this.selected){
                this.getGroupList();
                this.selected = [];
            }            
        },
        getGroupList (){
            if(this.selected){
                var data = {ids: this.selected}
                this.$parent.addGroupTag(this.selected);
                
            }

        },
    }
}
</script>