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
                                Template List
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
                                        <tr role="row" style="text-align: center">                                            
                                            <th>Ser.</th>
                                            <th>Name</th>
                                            <th>Message</th>                                            
                                            <th colspan="2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(template, index) in data.data" v-bind:key="template.id">
                                            <td>{{index+1}}</td>
                                            <td>{{ template.name }}</td>
                                            <td style="word-break: break-all" v-html="template.message"></td>                                            
                                            <td> 
                                                <!-- <router-link href="#"  v-bind:to="{name: 'ContactDetail', params: {id:contact.id}}" class="text-info" data-toggle="m-tooltip" title="Detail">
                                                    <i class='fa fa-folder'></i>
                                                </router-link> -->
                                                <router-link href="#"  v-bind:to="{name: 'AdminTemplateEdit', params: {id:template.id}}" class="text-info" data-toggle="m-tooltip" title="Edit">
                                                    <i class='fa fa-edit'></i>
                                                </router-link>
                                            
                                                <a @click.prevent="deleteTemplate(template.id,index)"  class="text-danger" href="#" data-toggle="m-tooltip" title="Delete">
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
                                    <select name="count" class="form-control m-input" @change="fetchTemplates()"  v-model="pagination.per_page" style="padding: 0.65rem 0.4rem">
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
                                    <vue-pagination  :pagination="pagination" @paginate="fetchTemplates()" :offset="4"> </vue-pagination>
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
        this.fetchTemplates();
        this.bindCurrentRoute();
    },
    methods: {
        Reload(){
            this.fetchTemplates();
        },
        // Fetch List
        fetchTemplates(page_url) {
            page_url = page_url || BASE_URL+'admin-api/templates?page='+this.pagination.current_page+'&per_page='+this.pagination.per_page;
            this.getPagiData(page_url);
        },

        deleteTemplate(id, index){
            var self = this;
            this.$deletePagiItem(self.data.data, index, self.pagination, 'Are you sure you want to delete this template?', BASE_URL+'admin-api/templates/' + id);
        }
    },

}
</script>
