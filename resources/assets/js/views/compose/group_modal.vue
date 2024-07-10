<!-- Group Modal -->
<template>
    <div id="group-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
                    <h3>Group List</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>                                
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <!-- <div class="mr-1"><a href="#" data-dismiss="modal" @click.prevent="insertGroup()" v-bind:class="selected.length ? 'btn m-btn--air btn-accent' : 'btn m-btn--air btn-accent disabled'"><span><i class="la la-plus"></i> <span>Insert {{selected.length}} Groups</span></span></a></div> -->
                        <br />
                        <table class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline collapsed" id="m_table_1" role="grid" aria-describedby="m_table_1_info">
                            <thead>
                                <tr role="row">
                                    <th><input type="checkbox" v-model="selectAll"></th>                                          
                                    <th>Name</th>
                                    <th>Contacts</th>                                            
                                    <th>Date Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(group, index) in modalData.groups" v-bind:key="index">
                                    <td><input type="checkbox" name="listselect" v-model="selected" :value="group.id" number></td>
                                    <td>{{ group.name }}</td>
                                    <td>{{ group.num_contacts }}</td>
                                    <td>{{ group.created_at }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" data-dismiss="modal" @click.prevent="insertGroup()" v-bind:class="selected.length ? 'btn m-btn--air btn-accent' : 'btn m-btn--air btn-accent disabled'"><span> <span>Insert {{selected.length}} Groups</span></span></a>
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
           selected: [],
        }
    },
    mounted(){
       this.baseUrl = BASE_URL;
       this.closeModal("#group-modal");
       this.showModal("#group-modal");
    },
    props:['modalData'],
    computed: {
        selectAll: {
            get: function () {
                return this.modalData.groups ? this.selected.length == this.modalData.groups.length : false;
            },
            set: function (value) { 
                var groups = this.modalData.groups;  
                if (value && groups) {   
                    for (var i = 0; i < groups.length; i++) {  
                        if(this.selected.indexOf(groups[i].id) === -1) {
                            this.selected.push(groups[i].id); 
                        }                        
                                     
                    }
                    
                }else{
                    this.selected = [];
                }                        
            }
        }
    },
    methods: {
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
        insertGroup (){
            if(this.selected){
                this.getGroupList();
                this.selected = [];
            }                
        },
        getGroupList (){
            if(this.selected){
                var data = {ids: this.selected}
                axios.post('api/groups-list', data).then((res) => 
                {  
                    var list = this.$processTagifySelectData(res.data.data,'name','id');                               
                    for(var i=0;i<list.length;i++){
                        list[i].code = list[i].code.toString();                       
                    }
                    this.$parent.addGroupTag(list);
                })
                .catch(function (error) {
                    console.log(error.response);
                });
            }

        },
    }
}
</script>