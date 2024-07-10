<template>
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <js-plugin :js-plugin="data.js_plugin"></js-plugin>
        <breadcrumb :breadcrumb-data="data.breadcrumb"></breadcrumb>
        <div class="row m-content">
            <div class="col-lg-12">
                <!--begin::Portlet-->
                <div class="m-portlet">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <span class="m-portlet__head-icon m--hide">
                                    <i class="la la-gear"></i>
                                </span>
                                <h3 class="m-portlet__head-text">
                                    Import Contacts
                                </h3>
                            </div>
                        </div>
                    </div>
                    
                    <!--begin::Form-->
                    <form v-show="!tableShow" class="m-form" @submit.prevent="uploadContact">
                        <div class="m-portlet__body">
                            <div class="m-form__section m-form__section--first">
                                <div class="form-group m-form__group row" :class="errors.has('file') || validationErrors.file ? 'has-error' : ''">
                                    <label class="col-lg-3 col-form-label"  for="file">File:<span class="required">*</span></label>
                                    <div class="col-lg-6">                                        
                                        <input type="file" id="file" ref="file" v-on:change="onChangeFileUpload()" class="form-control m-input"/>
                                        <p class="help-block">Supported file types: CSV (.csv).</p>
                                        <span class="m-form__help" v-if="errors.has('file') || validationErrors.file">
                                            {{ errors.first('file') || validationErrors.file[0] }}
                                        </span>
                                    </div>
                                    <div class="col-lg-1">
                                        <div class="mr-1">
                                            <a :href="data.exampleFile" download class="btn btn-sm btn-accent">
                                                <span><i class="la la-download"></i> <span>Example File</span></span>
                                            </a>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__foot m-portlet__foot--fit">
                            <div class="m-form__actions m-form__actions">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                    <!--end::Form-->
                    <!--begin::Form-->
                    <form v-show="tableShow" class="m-form" @submit.prevent="importContact">
                        <table v-show="tableShow" class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline collapsed" id="m_table_1" role="grid" aria-describedby="m_table_1_info" style="width: 100%;">
                            <thead>
                                <tr role="row">                                            
                                    <th v-for="item in responseData.maxColumns" v-bind:class="{ 'bg-danger': fieldName[item]=='noimport', 'bg-primary': fieldName[item]!='noimport' }">                                        
                                        <select v-model="fieldName[item]" v-validate="'required'" class="form-control m-input" style="border: 1px solid" @change="onChange($event, item)">
                                            <option v-for="(item, index) in responseData.fieldName" :value="index" :key="index">{{item}}</option>
                                        </select>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data, index) in contact" style="text-align: center;">
                                    <td v-for="(con, key) in data">{{ con }}</td>                                            
                                </tr>
                                            
                            </tbody>
                        </table>
                        <div class="m-portlet__foot m-portlet__foot--fit" style="display: none;">
                            <div class="m-form__actions m-form__actions">
                                <div class="custom-control custom-checkbox">
                                    <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                        <input name="excludeFirstRow" id="excludeFirstRow" type="checkbox" class="form-control m-input"> <span></span>
                                        Exclude the first row.
                                    </label>                                    
                                    <br />
                                    <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                        <input name="matchColumns" id="matchColumns" type="checkbox" class="form-control m-input"> <span></span>
                                        Match columns according to previous import.
                                    </label>                                    
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__foot m-portlet__foot--fit">                            
                            <div class="m-form__actions m-form__actions">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                    
                </div>
                <!--end::Portlet-->

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
      contact: {},
      fieldName: [],
      failList: [],
      responseData: {},
      file: {},
      tableShow: false,
      validationErrors: {},
      data:{}
    };
  },
  mounted(){
      this.create();
      this.bindCurrentRoute("ContactImport");
  },
  methods: {
    removeItem(array, item){
    for(var i in array){
        if(array[i]==item){            
            array[i] = 'noimport';
        }
    }
    },
    onChange(event, index) {
        console.log(event.target.value);
        let value = event.target.value;
        if(value != "noimport"){
            console.log(this.fieldName.includes(value));
            if(this.fieldName.includes(value)){
                this.removeItem(this.fieldName, value);
                this.fieldName[index] = value;
            }
        }
        console.log(this.fieldName);
    },
    onChangeFileUpload(){
    this.file = this.$refs.file.files[0];
    },

    previewFiles(event) {
      console.log(event.target.files);
      this.file = event.target.files[0];
    },
    setUserPreference(jsonData){
        if(jsonData){            
            if(jsonData.excludeFirstRow){
                $("#excludeFirstRow").prop( "checked", true );
            }
            if(jsonData.matchColumns){
                $("#matchColumns").prop( "checked", true );
            }
            if(jsonData.fieldName && jsonData.matchColumns){
                jsonData.fieldName.unshift('');
                this.fieldName = jsonData.fieldName; 
            }                                         
        }
    },
    create(){
        var url = BASE_URL+'admin-api/contact-import';

        axios.get(url).then((res) => 
        { 
            this.data = res.data;
            this.$setDocumentTitle(this.data.title);
        })
        .catch(function (error) {
            console.log(error.response);
        });

    },
    // Import Contact
    importContact() {

        this.$validator.validateAll().then((result) => {
            console.log(result);            
            if(result == true){
                if(typeof commonLib != 'undefined'){
                    commonLib.blockUI({target: ".m-content",animate: true,overlayColor: 'none'});
                }                

                let formData = {
                    'fieldName': this.fieldName,
                    'fileName': this.responseData.fileName,
                    'originalFilename': this.responseData.originalFilename,
                    'excludeFirstRow': $('#excludeFirstRow').is(":checked"),
                    'matchColumns': $('#matchColumns').is(":checked"),
                };
                //console.log(formData);

                axios.post(BASE_URL+"admin-api/contact-import-create",formData)
                .then((res) => 
                {                    
                    commonLib.iniToastrNotification(res.data.response_msg.type, res.data.response_msg.title, res.data.response_msg.message);
                    if(res.data.response_msg.type=='success'){
                        //this.$router.push({name:'ContactList'});
                        this.$router.push({name:'ContactGroupList', params: { group_id: res.data.response_msg.data.groupId }});
                    }
                    commonLib.unblockUI(".m-content");
                })
                .catch(function (error) {
                    console.log(error);
                    //vm.validationErrors = error.response.data;
                    commonLib.unblockUI(".m-content");
                });
            }
        });
    },

    // Upload Contact
    uploadContact() {
        this.$validator.validateAll().then((result) => { 
            if(result == true){
                if(typeof commonLib != 'undefined'){
                    commonLib.blockUI({target: ".m-content",animate: true,overlayColor: 'none'});
                }
                var vm = this;
                let formData = new FormData();
                formData.append('file', this.file);

                axios.post(BASE_URL+"admin-api/contact-import",formData,
                    {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then((res) => 
                {                    
                    
                    if(res.data.response_msg.type=='success'){
                        this.contact = res.data.response_msg.data.contacts;
                        this.responseData = res.data.response_msg.data;
                        this.setUserPreference(res.data.response_msg.data.importJsonData);
                        this.tableShow = true;
                    }else{
                        commonLib.iniToastrNotification(res.data.response_msg.type, res.data.response_msg.title, res.data.response_msg.message);
                    }
                    //this.$router.push({name:'ContactList'});
                    commonLib.unblockUI(".m-content");
                })
                .catch(function (error) {
                    console.log(error);
                    //vm.validationErrors = error.response.data;
                    commonLib.unblockUI(".m-content");
                });
                 
            }

        }); 
    }
  }
};
</script>
