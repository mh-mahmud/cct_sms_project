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
                                {{ data.group.name }}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">                     
                    <!--begin: Datatable -->
                    <div id="m_table_1_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                        <div class="row" style="padding: 0px;margin: 0px;">
                            <div class="col-sm-12" style="padding: 0px;">
                                <div class="btn-group-sm" role="group" aria-label="Group" style="float: right">
                                    <button type="button" class="btn btn-danger" @click.prevent="deleteSelectedContactFromGroup()">Delete Selected</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline collapsed sortable" id="m_table_1" role="grid" aria-describedby="m_table_1_info" style="width: 1044px;">
                                    <thead>
                                        <tr role="row" style="text-align: center">
                                            <th><input type="checkbox" v-model="selectAll"></th>                                          
                                            <th>Ser.</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Phone</th>
                                            <th>Company</th>                                            
                                            <th colspan="2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(contact, index) in data.data" v-bind:key="contact.id" style="text-align: center">
                                            <td><input type="checkbox" name="listselect" v-model="selected" :value="contact.id" number></td>
                                            <td>{{index+1}}</td>
                                            <td>{{ contact.first_name }}</td>
                                            <td>{{ contact.last_name }}</td>
                                            <td>{{ contact.phone }}</td>
                                            <td>{{ contact.company }}</td>                                            
                                            <td> 
                                                <!-- <router-link href="#"  v-bind:to="{name: 'ContactDetail', params: {id:contact.id}}" class="text-info" data-toggle="m-tooltip" title="Detail">
                                                    <i class='fa fa-folder'></i>
                                                </router-link> -->
                                                <!-- <router-link href="#"  v-bind:to="{name: 'ContactEdit', params: {id:contact.id}}" class="text-info" data-toggle="m-tooltip" title="Edit">
                                                    <i class='fa fa-edit'></i>
                                                </router-link> -->
                                            
                                                <a @click.prevent="deleteContactFromGroup(contact.id,index)"  class="text-danger" href="#" data-toggle="m-tooltip" title="Delete">
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
                                    <vue-pagination  :pagination="pagination" @paginate="fetchTextList()" :offset="4"> </vue-pagination>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                                
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->	
            <!-- contact modal -->
                <contact-modal ref="contactModal" v-bind:modal-data="modalData"></contact-modal>	        
        </div>
    </div>
</template>

<script>
import AppComponent from '../../components/AppComponent'
import ContactModal from './contact_modal'
export default {
  extends: AppComponent,
    components:{
        ContactModal
    },
    data() {
        return {
            data: {},
            pagination: {},
            modalData: {},
            selected: [],
        }
    },
    mounted() { 
        if(this.$route.params.group_id){
            this.setActiveMenue('AdminGroupList');
            this.fetchContacts();
            this.bindCurrentRoute();
        }
    },
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
        setActiveMenue(currentRouteName){
            jQuery(".m-menu__item--active").removeClass("m-menu__item--active");        
            var routeId = "#"+currentRouteName;
            jQuery(routeId).closest("li").addClass("m-menu__item--active");
            jQuery(routeId).closest("li.sub-menu").addClass("m-menu__item--active");
            jQuery(routeId).closest("li.parent-menu").addClass("m-menu__item--active");
        },
        addAllGroupTag(url){
            if(typeof commonLib != 'undefined'){
                commonLib.blockUI({target: ".m-content",animate: true,overlayColor: 'none'});
            }
            let vm = this;
            var refUrl = url;
            axios.get(refUrl).then((res) => 
            {                
                commonLib.unblockUI(".m-content");
                location.reload();
                //this.fetchContacts();
            })
            .catch(function (error) {
                console.log(error.response);
                commonLib.unblockUI(".m-content");
            });
        },
        addGroupTag(contacts){             
            if(typeof commonLib != 'undefined'){
                commonLib.blockUI({target: ".m-content",animate: true,overlayColor: 'none'});
            }
            let vm = this;
            var refUrl = BASE_URL+'admin-api/add-contact/'+this.$route.params.group_id;
            axios.post(refUrl, {'contacts': contacts}).then((res) => 
            {                
                this.data = res.data;
                this.$setDocumentTitle(this.data.title);
                commonLib.unblockUI(".m-content");
                location.reload();
                //this.fetchContacts();
            })
            .catch(function (error) {
                console.log(error.response);
                commonLib.unblockUI(".m-content");
            });
        },
        // Fetch List
        fetchContacts(page_url) {
            page_url = page_url || BASE_URL+'admin-api/contact-group-list/'+this.$route.params.group_id+'?page='+this.pagination.current_page;
            this.getPagiData(page_url);
        },
        deleteSelectedContactFromGroup(){
            if(this.selectAll){
                this.deleteAllSelectedContactFromGroup();
                return;
            }
            if(this.selected.length){
                var self = this;
                let postData = {'ids': this.selected};
                let href = BASE_URL+'admin-api/contact-delete/'+ this.$route.params.group_id;
                let msg = 'Are you sure you want to delete these contacts?';

                bootbox.confirm(msg, function(result) {
                    if (result == true) {
                        commonLib.blockUI({target: ".m-content", animate: true, overlayColor: 'none', top:'45%'});
                        axios.post(href,postData).then((res) => 
                        {
                            self.fetchContacts();
                            commonLib.iniToastrNotification(res.data.response_msg.type, res.data.response_msg.title, res.data.response_msg.message);
                            commonLib.unblockUI(".m-content");
                            location.reload();                            
                            
                        })
                        .catch( function(error) {                            
                            commonLib.iniToastrNotification("warning", "Warning", "Item Could not delete");
                            commonLib.unblockUI(".m-content");
                        }); 
                    }
                });
            //this.$deleteSelectedItem(ids, 'Are you sure you want to delete these contacts?', 'api/contact-delete/'+ this.$route.params.group_id);
            }
        },
        deleteAllSelectedContactFromGroup(){
                var self = this;                
                let href = BASE_URL+'admin-api/contact-delete-selectall/'+ this.$route.params.group_id;
                let msg = 'Are you sure you want to delete these contacts?';

                bootbox.confirm(msg, function(result) {
                    if (result == true) {
                        commonLib.blockUI({target: ".m-content", animate: true, overlayColor: 'none', top:'45%'});
                        axios.get(href).then((res) => 
                        {
                            self.fetchContacts();
                            commonLib.iniToastrNotification(res.data.response_msg.type, res.data.response_msg.title, res.data.response_msg.message);
                            commonLib.unblockUI(".m-content");
                            location.reload();
                            
                        })
                        .catch( function(error) {                            
                            commonLib.iniToastrNotification("warning", "Warning", "Item Could not delete");
                            commonLib.unblockUI(".m-content");
                        }); 
                    }
                });
        },
        deleteContactFromGroup(id, index){
            this.selected.push(id);
            this.deleteSelectedContactFromGroup();
            // var self = this;
            // this.$deletePagiItem(self.data.data, index, self.pagination, 'Are you sure you want to delete this contact?', 'api/contact-delete/' + id + '/' + this.$route.params.group_id);            
        }
    },

}
</script>
