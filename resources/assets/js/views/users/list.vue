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
                                User List
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <!--begin: Datatable -->
                    <div id="m_table_1_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline collapsed" id="m_table_1" role="grid" aria-describedby="m_table_1_info" style="width: 1044px;">
                                    <thead>
                                        <tr role="row">
                                            <th>Name</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>User Type</th>
                                            <th>Status</th>
                                            <th colspan="2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(user, index) in data.data" v-bind:key="user.id">
                                            <td>{{ user.first_name }}&nbsp;{{ user.last_name }}</td>
                                            <td>{{ user.username }}</td>
                                            <td>{{ user.email }}</td>
                                            <td>{{data.userType[user.type]}}</td>
                                            <td>{{data.userStatus[user.status]}}</td>
                                            <td> 
                                                <router-link href="#"  v-bind:to="{name: 'UserDetail', params: {id:user.id}}" class="text-info" data-toggle="m-tooltip" title="Detail">
                                                    <i class='fa fa-folder'></i>
                                                </router-link>
                                                <router-link href="#"  v-bind:to="{name: 'UserEdit', params: {id:user.id}}" class="text-info" data-toggle="m-tooltip" title="Edit">
                                                    <i class='fa fa-edit'></i>
                                                </router-link>
                                           
                                                <a @click.prevent="deleteUser(user.id,index)"  class="text-danger" href="#" data-toggle="m-tooltip" title="Delete">
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
                                    <vue-pagination  :pagination="pagination" @paginate="fetchUsers()" :offset="4"> </vue-pagination>
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
        this.fetchUsers();
        this.bindCurrentRoute();
    },
    methods: {
        // Fetch Users List
        fetchUsers(page_url) {
            page_url = page_url || 'api/user-list?page='+this.pagination.current_page;
            this.getPagiData(page_url);

        },

        deleteUser(id, index){
            var self = this;
            this.$deletePagiItem(self.data.data, index, self.pagination, 'Are you sure you want to delete this user?', 'api/users/' + id);
        }
       
    },

}
</script>
