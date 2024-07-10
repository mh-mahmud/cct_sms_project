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
                                    Compose
                                </h3>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="m-form" @submit.prevent="composeUpdate">
                        <div class="m-portlet__body">
                            <div class="m-form__section m-form__section--first">
                                
                                <div class="form-group m-form__group row" :class="errors.has('to') || validationErrors.to ? 'has-error' : ''">
                                    <label class="col-lg-3 col-form-label"  for="to">To:<span class="required">*</span></label>
                                    <div class="col-lg-6">   

                                        <bootstrap-tag-input name="to" id="to" v-bind:remote-url="'api/search-contact-list'" :is-required="true" v-bind:data-params="{'model':'to','initialData':initialData}"></bootstrap-tag-input>

                                        <a href="javascript:void(0)" @click.prevent="bindModalData(data)" data-toggle="modal" data-target="#contact-modal" class="pull-right"><i class="m-menu__link-icon flaticon-user" style="font-size: 1.0rem;"></i><span><label style="cursor:pointer;margin-left:1.5px">Contacts</label></span></a>
                                        <a href="javascript:void(0)" style="margin-right: 10px" @click.prevent="bindModalData(data)" data-toggle="modal" data-target="#group-modal" class="pull-right"><i class="m-menu__link-icon flaticon-user" style="font-size: 1.0rem;"></i><span><label style="cursor:pointer;margin-left:1.5px">Groups</label></span></a>
                                        <br />
                                        <span class="m-form__help" v-if="errors.has('to') || validationErrors.to">
                                            {{ errors.first('to') || validationErrors.to[0] }}
                                        </span>
                                    </div>   
                                </div>
                                <div class="form-group m-form__group row" :class="errors.has('from') || validationErrors.from ? 'has-error' : ''">
                                    <label class="col-lg-3 col-form-label"  for="from">From:<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <v-select data-vv-as="From" name="from" v-validate="'required'" :options="data.did" v-model="compose.from" @keydown.enter.native="preventOnEnter($event)"></v-select>  
                                        <!-- <input data-vv-as="From" name="from" v-validate="'required|max:13'"  v-model="compose.from" type="text" class="form-control m-input" placeholder="Enter Phone"> -->
                                        <span class="m-form__help" v-if="errors.has('from') || validationErrors.from">
                                            {{ errors.first('from') || validationErrors.from[0] }}
                                        </span>
                                    </div>    
                                </div>
                                <div class="form-group m-form__group row" :class="errors.has('message') || validationErrors.message ? 'has-error' : ''">
                                    <label class="col-lg-3 col-form-label"  for="message">Message:<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <textarea data-vv-as="Message" name="message" v-validate="'required|max:'+data.sms_text_size"  v-model="message" style="height:150px;" type="text" class="form-control m-input" placeholder="Enter Message"></textarea>
                                        <span class="limiter">{{ calculateSMSParts }}</span>
                                        <a href="javascript:void(0)" @click.prevent="bindModalData(data)" data-toggle="modal" data-target="#template-modal" class="pull-right"><span><label style="cursor:pointer;">Insert Template</label></span></a>
                                        <br />
                                        <span class="m-form__help" v-if="errors.has('message') || validationErrors.message">
                                            {{ errors.first('message') || validationErrors.message[0] }}
                                        </span>                                     
                                    </div>    
                                </div>
                                <a href="#" @click.prevent="scheduleShow = !scheduleShow"><span><label style="cursor:pointer;">{{scheduleShow ? 'Cancel Schedule' : 'Schedule Message'}}</label></span></a>
                                <div v-show="scheduleShow">                                
                                    <div class="form-group m-form__group row" :class="errors.has('scheduleDate') || validationErrors.scheduleDate ? 'has-error' : ''">
                                        <label class="col-lg-3 col-form-label"  for="scheduleDate">Schedule:<span class="required">*</span></label>
                                        <div class="col-lg-6">
                                            <date-picker class="form-control m-input date-time-picker" data-vv-as="Schedule" name="scheduleDate" v-validate="this.scheduleShow ? 'required' :''" v-model="compose.scheduleDate" :config="dateOptions" autocomplete="off"></date-picker>
                                            <span class="m-form__help" v-if="errors.has('scheduleDate') || validationErrors.scheduleDate">
                                                {{ errors.first('scheduleDate') || validationErrors.scheduleDate[0] }}
                                            </span>
                                        </div>    
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-lg-3 col-form-label"  for="time_zone">Time Zone:</label>
                                        <div class="col-lg-6">
                                            <v-select data-vv-as="Time Zone" name="time_zone" :options="data.time_zone" v-model="compose.time_zone" @keydown.enter.native="preventOnEnter($event)"></v-select>                                        
                                        </div>    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__foot m-portlet__foot--fit">
                            <div class="m-form__actions m-form__actions">
                                <button type="submit" class="btn btn-primary">Send</button>
                            </div>
                        </div>
                    </form>
                    <!--end::Form-->
                    
                </div>
                <!--end::Portlet-->
                <!-- group modal -->
                <group-modal v-bind:modal-data="modalData"></group-modal>
                 <!-- contact modal -->
                <contact-modal v-bind:modal-data="modalData"></contact-modal>
                <!-- template modal -->
                <template-modal v-bind:modal-data="modalData"></template-modal>
            </div>
        </div>
    </div>   
</template>

<script>
import AppComponent from '../../components/AppComponent'
import BootstrapTagInput from '../../components/BootstrapTagInput'
import GroupModal from './group_modal'
import ContactModal from './contact_modal'
import TemplateModal from './template_modal'
export default {
  extends: AppComponent,
  components:{
    GroupModal, TemplateModal, ContactModal, BootstrapTagInput
  },
  data() {
    return {
      dateOptions: {
          format: 'YYYY-MM-DD HH:mm:ss',
          useCurrent: false,showClear: true,showClose: true
      },
      modalData: {},
      baseUrl: "",
      toList: [],      
      compose: {},
      message: "",
      scheduleShow: true,
      validationErrors: {},
      data:{},
      to:{},
      initialData:[]
    };
  },
  computed: {
      calculateSMSParts(){
        var
            smsType,
            smsLength = 0,
            smsCount = -1,
            charsLeft = 0,
            text = this.message,
            isUnicode = false;

            for(var charPos = 0; charPos < text.length; charPos++){
                switch(text[charPos]){
                    case "\n": 
                    case "[":
                    case "]":
                    case "\\":
                    case "^":
                    case "{":
                    case "}":
                    case "|":
                    case "€":
                        smsLength += 2;
                    break;

                    default:
                        smsLength += 1;
                }

                //!isUnicode && text.charCodeAt(charPos) > 127 && text[charPos] != "€" && (isUnicode = true)
                if(text.charCodeAt(charPos) > 127 && text[charPos] != "€")
                isUnicode = true;
            }

            if(isUnicode){
                smsType = this.data.sms_text_lengths_unicode;//lengths.unicode;
                this.data.sms_text_size = 201;
            }
            else{
                smsType = this.data.sms_text_lengths_ascii;//lengths.ascii;
                this.data.sms_text_size = 459;
            }

            for(var sCount = 0; sCount < this.data.sms_text_part; sCount++){

                //this.cutStrLength = smsType[sCount];
                if(smsLength <= smsType[sCount]){

                    smsCount = sCount + 1;
                    charsLeft = smsType[sCount] - smsLength;
                    break
                }
            }

            //if(this.cut) e.val(text.substring(0, this.cutStrLength));
            smsCount == -1 && (smsCount = this.data.sms_text_part, charsLeft = 0);

            if (typeof smsCount === 'undefined') {
                smsCount = 0;
            }

            return "Characters: " + smsLength + "/" + this.data.sms_text_size + " | " + "Parts: " + smsCount + "/" + this.data.sms_text_part;

    },
    charactersLeft() {
        var char = this.message.length,
            limit = this.data.sms_text_size;
        var remaining = limit - char;
          if(char > limit)
            char = limit;

        return "Characters: " + char + "/" + limit;
      },
      partsLeft() {
          var parts = this.message.length,limit = this.data.sms_text_part,part_size = this.data.sms_text_part_size;
          parts = Math.ceil(parts/part_size);
          var remaining = limit - parts;
          if(parts > limit)
            parts = limit;

        return "Parts: " + parts + "/" + limit;
      }
  },
  mounted(){
      if(this.$route.params.id){ 
          let id = this.$route.params.id;
          this.getDetails(id);          
          this.bindCurrentRoute();
      }
  },
  methods: {
    preventOnEnter(event){        
        event.preventDefault();       
    },
    setData () {

        var array1 = this.$processTagifySelectData(this.data.data.groupContacts,'name','id');        
        var array2 = this.$processTagifySelectData(this.data.data.contacts,'phone','phone');
        this.initialData = array1.concat(array2);
        this.compose.time_zone = this.data.data.time_zone;
        this.message = this.data.data.sms_text;        
        this.compose.from = this.data.data.sms_from;
        this.compose.scheduleDate = this.data.data.first_send_time;
    },
    getDetails(id){
        var url = 'api/compose-detail/'+id;
        axios.get(url).then((res) => 
        { 
            this.data = res.data;             
            this.setData();                         
            this.$setDocumentTitle(this.data.title);
        })
        .catch(function (error) {
            console.log(error.response);
        });

    },
    // Add/Update Contact
    composeUpdate() {
        this.$validator.validateAll().then((result) => { 
            if(result == true){
                if(typeof commonLib != 'undefined'){
                    // commonLib.blockUI({target: ".m-content",animate: true,overlayColor: 'none'});
                }
                var vm = this;
                this.compose.to = this.to.value;
                this.compose.message = this.message;
                this.compose.scheduleShow = this.scheduleShow;
                axios.post('api/compose-update/'+this.$route.params.id, this.compose).then((res) => 
                {
                    commonLib.iniToastrNotification(res.data.response_msg.type, res.data.response_msg.title, res.data.response_msg.message);
                    if(res.data.response_msg.type == 'success'){
                        this.compose = {};
                        this.message = "";
                        this.$router.push({name:'ScheduleList'});
                    }
                    commonLib.unblockUI(".m-content");
                })
                .catch(function (error) {
                    vm.validationErrors = error.response.data;
                    commonLib.unblockUI(".m-content");
                });
            }
        });
    },
    // bind data to use on modal
    bindModalData(data){
        this.modalData = data;
    },
    addGroupTag(tag){             
        this.to.addTags(tag);               
    }
  }
};
</script>
