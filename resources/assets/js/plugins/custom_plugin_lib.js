const CustomPluginLib = {
    install(Vue, options) {
        Vue.prototype.$printData = (dataset) => {
            console.log(dataset);
        };
        /**
         * delete table data item
         */
        Vue.prototype.$deleteItem = (panel, elem, msg, url) => {
            if (!msg)
                msg = "Are you sure?";
            var href = url;
            var trId = $(elem).parents('tr').attr("id");

            bootbox.confirm(msg, function(result) {
                if (result == true) {
                    commonLib.blockUI({target: ".m-content", animate: true, overlayColor: 'none'});
                    axios.delete(href).then((res) => 
                    {
                        commonLib.iniToastrNotification(res.data.response_msg.type, res.data.response_msg.title, res.data.response_msg.message);
                        commonLib.unblockUI(".m-content");
                        $(elem).parents('tr').remove()
                    })
                    .catch( function(error) {
                        commonLib.iniToastrNotification("warning", "Warning", "Could not delete Item");
                        commonLib.unblockUI(".m-content");
                    }); 
                }
            });
        };
        /**
         * delete data item (universal delete)
         */
        Vue.prototype.$deleteDataItem = (data, index, url, msg, blockUiTarget = ".m-content") => {
            if (!msg)
                msg = "Are you sure?";
            var href = url;

            bootbox.confirm(msg, function(result) {
                if (result == true) {
                    commonLib.blockUI({target: blockUiTarget, animate: true, overlayColor: 'none'});
                    axios.delete(href).then((res) => 
                    {
                        commonLib.iniToastrNotification(res.data.response_msg.type, res.data.response_msg.title, res.data.response_msg.message);
                        commonLib.unblockUI(blockUiTarget);
                        data.splice(index, 1);
                    })
                    .catch( function(error) {
                        commonLib.iniToastrNotification("warning", "Warning", "Could not delete Item");
                        commonLib.unblockUI(blockUiTarget);
                    }); 
                }
            });
        };
        Vue.prototype.$isFileExists = (filePath) =>{
            axios.get('api/file-exists-check?filepath='+filePath).then((res) => 
            { 
                return true;
            })
            .catch(function (error) {
                console.log(error);
                return false;
            });  
        };
        /**
         * get str/array length
         * @param {*} str 
         */
        Vue.prototype.$getLength = (str) =>{
            if(str){
                return str.length;
            }
            
        };
        Vue.prototype.$getLength = () =>{
            $("input.vs__search").attr('val',"");  
            
        };
        /**
         * get current date
         * @param {*} format
         */
        Vue.prototype.$getCurrentDate = (format) =>{
            if(format){
                return moment().format(format);
            }
        };
        Vue.prototype.$getFormateDate = (date) =>{
            return moment(date, 'YYYY-MM-DD hh:mm').format('DD-MM-YYYY hh:mm A');
        };
        Vue.prototype.$setDocumentTitle = (title) =>{
            document.title = title;
        };
        /**
         * get sub-string of a string
         */
        Vue.prototype.$getSubString = (str, start, end) =>{ 
            if(str){
                return str.substr(start,end);
            }
            
        };
        /**
         * check privileges
         */
        Vue.prototype.$checkEvPrivilege = (privileges,index) =>{ 
            if(privileges.indexOf(index) != -1 || privileges.indexOf('*') != -1){
                return true;
            }else{
                return false
            }
            
        };
        /**
         * delete selected items
         * @param {array} data 
         * @param {int} index 
         * @param {array} pagination 
         * @param {string} msg 
         * @param {string} url 
         */
        Vue.prototype.$deleteSelectedItem = (postData, msg, url) => {
            if (!msg)
                msg = "Are you sure?";
            var href = url;            

            bootbox.confirm(msg, function(result) {
                if (result == true) {
                    commonLib.blockUI({target: ".m-content", animate: true, overlayColor: 'none', top:'45%'});
                    axios.post(href,postData).then((res) => 
                    {
                        //console.log('sd');
                        location.reload();
                        commonLib.iniToastrNotification(res.data.response_msg.type, res.data.response_msg.title, res.data.response_msg.message);
                        commonLib.unblockUI(".m-content");
                        
                    })
                    .catch( function(error) {
                        console.log(error);
                        commonLib.iniToastrNotification("warning", "Warning", "Item Could not delete");
                        commonLib.unblockUI(".m-content");
                    }); 
                }
            });
        };
        /**
         * delete pagination items
         * @param {array} data 
         * @param {int} index 
         * @param {array} pagination 
         * @param {string} msg 
         * @param {string} url 
         */
        Vue.prototype.$deletePagiItem = (data, index, pagination, msg, url) => {
            if (!msg)
                msg = "Are you sure?";
            var href = url;
            var method = {'_method': 'DELETE'};

            bootbox.confirm(msg, function(result) {
                if (result == true) {
                    commonLib.blockUI({target: ".m-content", animate: true, overlayColor: 'none', top:'45%'});
                    axios.post(href, method).then((res) => 
                    {
                        data.splice(index, 1);
                        pagination.to = pagination.to -1;
                        pagination.total = pagination.total -1;
                        commonLib.iniToastrNotification(res.data.response_msg.type, res.data.response_msg.title, res.data.response_msg.message);
                        commonLib.unblockUI(".m-content");
                        
                    })
                    .catch( function(error) {
                        commonLib.iniToastrNotification("warning", "Warning", "Item Could not delete");
                        commonLib.unblockUI(".m-content");
                    }); 
                }
            });
        };

        /**
         * make pagination data
         */
        Vue.prototype.$makePagination = (meta, links) =>{ 
            return {
                current_page: meta.current_page,
                from: meta.from,
                to: meta.to,
                per_page: meta.per_page,
                total: meta.total,
                last_page: meta.last_page,
                next_page_url: links.next,
                prev_page_url: links.prev,
                first_page_url: links.first,
                last_page_url: links.last
                
            };
            
        };
        /**
         * get maximum valid date difference for date range search
         */
        Vue.prototype.$getValidDiffDate = (stDate, dateDiff) =>{ 
            var getStDate = moment(String(stDate), 'DD/MM/YYYY HH:mm').format('YYYY-MM-DD HH:mm');
            var formatStDate = new Date(getStDate);
            var dateDiffObj = moment(formatStDate).add(dateDiff, 'days');
            var dateDiff = moment(String(dateDiffObj)).format('DD/MM/YYYY HH:mm');
            return dateDiff;
        }
        /**
         * process v select data to use on dropdown
         * @param object data
         * @param str label
         * @param value code
         */
        Vue.prototype.$processVselectData = (data, label, code) =>{
            if(data.length){ 
                var arr = $.map(data, function(elm, index) {
                    return {'label': (elm[label]) ? elm[label] : elm[code], 'code': elm[code]};
                });
               return arr;
            }
            return [];
            
        };
        /**
         * process Tagify select data to use on dropdown
         * @param object data
         * @param str label
         * @param value code
         */
        Vue.prototype.$processTagifySelectData = (data, label, code) =>{
            if(data.length){ 
                var arr = $.map(data, function(elm, index) {
                    return {'code': elm[code], 'value': (elm[label]) ? elm[label] : elm[code]};
                });
               return arr;
            }
            return [];
            
        };
        
        /**
         * get schedule direction
         * @param str status         
         */
        Vue.prototype.$getScheduleDirection = (direction) =>{
            const SCHEDULE_DIRECTION = {
                I: 'INBOUND',
                O: 'OUTBOUND',
              };
              if(SCHEDULE_DIRECTION[direction])
                return SCHEDULE_DIRECTION[direction];
            return '';
        };
        /**
         * get schedule status
         * @param str status         
         */
        Vue.prototype.$getScheduleStatus = (status) =>{
            const SCHEDULE_STATUS = {
                R: 'READ',
                U: 'UNREAD',
                P: 'PENDING',
                S: 'SEND',
                F: 'FAILED',
              };
              if(SCHEDULE_STATUS[status])
                return SCHEDULE_STATUS[status];
            return '';
        };
        /**
         * get schedule status list
         * @param str status         
         */
        Vue.prototype.$getScheduleStatusList = () =>{
            const SCHEDULE_STATUS = {
                R: 'READ',
                U: 'UNREAD',
                P: 'PENDING',
                S: 'SEND',
                F: 'FAILED',
              };
            return SCHEDULE_STATUS;
        };
    }
}

export default CustomPluginLib;
