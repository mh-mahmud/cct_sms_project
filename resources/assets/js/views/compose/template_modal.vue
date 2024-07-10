<!-- Group Modal -->
<template>
    <div id="template-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
                <h3>Template List</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>                                
            </div>
            <div class="modal-body">
                <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline collapsed" id="m_table_1" role="grid" aria-describedby="m_table_1_info">
                    <thead>
                        <tr role="row">                                            
                            <th>Name</th>
                            <th>Message</th>                                            
                            <th colspan="2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(template, index) in modalData.templates" v-bind:key="template.id">
                            <td>{{ template.name }}</td>
                            <td style="word-break: break-all" v-html="template.message"></td>                                            
                            <td> 
                                <a @click.prevent="$parent.message = template.omessage"  data-dismiss="modal" class="text-danger" href="#" data-toggle="m-tooltip" title="Insert">
                                    <i class='fa fa-list'></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
        }
    },
    mounted(){
       this.baseUrl = BASE_URL;
       this.closeModal("#template-modal");
       this.showModal("#template-modal");
    },
    props:['modalData'],
    methods: {
        closeModal(refid){
            var self = this;
            $(refid).on('hidden.bs.modal', function () { 
                
            });

            
        },
        showModal(refid){
            var self = this;
            $(refid).on('shown.bs.modal', function () { 
              
            });
        }
    }
}
</script>