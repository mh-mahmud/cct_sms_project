<template>
    <div class="container">
         <div class="modal fade" id="upload-leads" tabindex="-1" role="dialog" aria-labelledby="upload-leads" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="upload-leads">Upload Leads</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                   
                    <form @submit.prevent="uploadLeads" enctype="multipart/form-data" id="leads-form">
                        <div class="modal-body">
                            <div class="form-group" :class="errors.has('file') || validationErrors.file ? 'has-error' : ''">
                                <label for="file">Leads CSV File<span class="required">*</span></label>
                                <input data-vv-as="Leads CSV File" name="file" v-validate="'required'" type="file" id="file" ref="file" v-on:change="handleFileUpload()" class="form-control-file no-border">
                                <span class="m-form__help" v-if="errors.has('file') || validationErrors.file">
                                    {{ errors.first('file') || validationErrors.file[0] }}
                                </span>
                            </div>
                            <div class="form-group">
                                <label class="m-checkbox m-checkbox--solid m-checkbox--state-danger">
                                    <input type="checkbox" name="delete_emails" v-model="form.delete_emails"> Delete all emails of this campaign.
                                    <span></span>
							    </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            file: {},
            validationErrors: {},
            form:{}
        }
    },
    props:['modalData'],
    mounted() { 
        this.closeLeadsModal();
        jQuery("#current-route").val(this.$route.name);

    },
    
    methods: {
        handleFileUpload(){
         this.file = this.$refs.file.files[0];
        },
        uploadLeads() {
            this.$validator.validateAll().then((result) => { 
                if(result == true){
                    if(typeof commonLib != 'undefined'){
                        commonLib.blockUI({target: ".modal-content",animate: true,overlayColor: 'none'});
                    }
                    var campId = this.modalData.id;
                    let formData = new FormData();
                    var vm = this;
                    formData.append('campaign_id', campId);
                    formData.append('file', this.file);
                    formData.append('delete_emails', this.form.delete_emails);
                    axios.post('api/upload-leads', 
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    ).then((res) => 
                    {
                        this.file = {};
                        this.form = {};
                        document.getElementById("leads-form").reset();
                        vm.validationErrors = {};
                        
                        this.$parent.fetchCampaigns();
                        commonLib.iniToastrNotification(res.data.response_msg.type, res.data.response_msg.title, res.data.response_msg.message);
                        commonLib.unblockUI(".modal-content");
                        commonLib.closeBootstrapModal("#upload-leads");
                    })
                    .catch(function (error) {
                        vm.validationErrors = error.response.data;
                        commonLib.unblockUI(".modal-content");
                    }); 
                }

            });

        },
        closeLeadsModal(){
            var self = this;
            $('#upload-leads').on('hidden.bs.modal', function () {
                self.file = {};
                self.form = {};
                document.getElementById("leads-form").reset();
                self.validationErrors = {};
                self.errors.clear();
            });

            
        }
       
       
    },

}
</script>
