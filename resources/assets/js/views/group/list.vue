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
                                Group
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <!--begin: Datatable -->
                    <div id="m_table_1_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline collapsed sortable" id="m_table_1" role="grid" aria-describedby="m_table_1_info" style="width: 1044px;">
                                    <thead>
                                        <tr role="row" style="text-align:center">
                                            <th>Ser.</th>
                                            <th>Group Name</th>
                                            <th>Number of Contacts</th>
                                            <th colspan="2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(list, index) in data.data" v-bind:key="list.id" style="text-align:center">
                                            <td>{{index+1}}</td>
                                            <td>{{ list.name }}</td>
                                            <td>
                                                <router-link href="#"  v-bind:to="{name: 'ContactGroupList', params: {group_id:list.id}}" class="text-info" data-toggle="m-tooltip" title="Detail">
                                                    <span class="badge badge-default">{{ list.num_contacts }} contacts</span>
                                                </router-link>
                                            </td>
                                            <td>
                                                <router-link href="#"  v-bind:to="{name: 'GroupEdit', params: {id:list.id}}" class="text-info" data-toggle="m-tooltip" title="Edit">
                                                    <i class='fa fa-edit'></i>
                                                </router-link>
                                            
                                                <a @click.prevent="deleteUser(list.id,index)"  class="text-danger" href="#" data-toggle="m-tooltip" title="Delete">
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
                                    <vue-pagination  :pagination="pagination" @paginate="fetchGroups()" :offset="4"> </vue-pagination>
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
            data: {},
            pagination: {},
        }
    },
    mounted() { 
        this.fetchGroups();
        this.bindCurrentRoute();
    },
    methods: {
        Reload(){
            this.fetchGroups();
        },
        // Fetch List
        fetchGroups(page_url) {
            page_url = page_url || 'api/group-list?page='+this.pagination.current_page;
            this.getPagiData(page_url);

        },
        deleteUser(id, index){
            var self = this;
            this.$deletePagiItem(self.data.data, index, self.pagination, 'Are you sure you want to delete this list?', 'api/groups/' + id);
        }
       
    },

}
</script>
