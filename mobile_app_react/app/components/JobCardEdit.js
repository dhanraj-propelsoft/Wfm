import React, { Component } from "react";
import { StyleSheet,View ,Platform,ScrollView,Image,TouchableOpacity,Dimensions,PixelRatio,AsyncStorage } from "react-native";

import { Container,Icon,Badge, Content, Accordion,Button,Item,  Picker,
         DatePicker,Toast ,Text,Textarea,Input,InputGroup,Right ,Spinner  as Loader,CardItem,Card,  Form,Label,Grid,Col,ListItem,Left,Body,CheckBox,Title,Subtitle  } from "native-base";

import ImagePicker from 'react-native-image-picker';

import axios from 'react-native-axios';
import _ from 'lodash';
import RNFetchBlob from 'react-native-fetch-blob';
import Config from 'react-native-config';
import Autocomplete from 'react-native-autocomplete-input';
import Modal from "react-native-modal";   

//import {_DE_Quan,_IN_Quan,getItemData,updateItemData}  from '../function';
 
import   Spinner  from 'react-native-loading-spinner-overlay';

//import toastr from './CustomToaster';

const API_URL=Config.API_URL;


const ScreensHeading = [
  { title: "Job Header"},
  { title: "Photos" },
  { title: "CheckList" },
  { title: "Job Items" }
];



const {
  width: SCREEN_WIDTH,
  height: SCREEN_HEIGHT,
} = Dimensions.get('window');

const scale = SCREEN_WIDTH / 320;
export function normalize(size) {
  const newSize = size * scale 
  if (Platform.OS === 'ios') {
    return Math.round(PixelRatio.roundToNearestPixel(newSize))
  } else {
    return Math.round(PixelRatio.roundToNearestPixel(newSize)) - 2
  }
}


const options={
  title: 'Upload Image',
  takePhotoButtonTitle: 'Take a photo with your camera',
  storageOptions: {
              cameraRoll: true,
  },
  maxWidth:800,
  maxHeight:600
}

export const toastr = {
  showToast: (message, duration ) => {
    Toast.show({
      text: message,
      duration,
      position: 'bottom',
      textStyle: { textAlign: 'center' },
    });
  },
};
 



export default class JobCardEdit extends Component {

  constructor(props) {
    super(props);
    this.state = {


      // Job card Data
      JobCardNumber:'',
      VehicleNumber:'',
      VehicleList:{},
      VehicleCheckList:{},
      CheckedListData:[],
      CheckListComments:[],
      CheckedListKey:[],
      VehicleId:'',
      VehicleName:'',
      VehicleComplaints:'',
      CustomerId:'',
      Customer:'',
      CustomerPhone:'',
      CustomerEmail:'',
      JobCardDate:'',
      Person_id:'',
      JobCardDueDate:'',
      height: 0,
      PrevState:"",
      type:'job_card',
      user_type:'',
      Customer_address:'',
      organization_id:'',
      transaction_id:'',
      JobStatuses:[],
      JobStatus:3,
      JobcardItems:[],
      query:'',
    //  SelectedJobItemList:[],
      JobcardCheckedItems:[],
      SelectedJobItemData:[],

      DefalutItemStatus:'',
      JobItemStatuses:[],

      // Image
      Images_Ins:[],
      Images_Ins_uploaded:[],
      ImageData_Ins:[],
      ImageName_Ins:[],


      Images_IP:[],
      Images_IP_uploaded:[],
      ImageData_IP:[],
      ImageName_IP:[],
      Images_RD:[],
      
      Images_RD_uploaded:[],
      ImageData_RD:[],
      ImageName_RD:[],
     


      loading:true,
      submissionLoading:false,
      loadingSpinner:false,

      Token:'',
      ConformPopup:false,
      AlternativeNumber:''

      //FORM DATA
  
    }

   
  }

  ConfirmationPopup = () =>{


  return ( <Modal isVisible={this.state.ConformPopup} onRequestClose={() => {this.setState({ConformPopup:!this.state.ConformPopup})}}>
  <View style={[styles.ModalContent]}>
      <Content >
          <View style={{flexDirection:'row'}}>
              <Text style={{alignContent:'flex-start',marginTop:'auto',marginBottom:'auto',fontWeight:'700'}}>Confirm your phone number</Text>
{/*              
              <Button title="Hide modal" onPress={()=>{this.setState({ConformPopup:!this.state.ConformPopup})}} transparent style={{marginLeft:'auto',marginTop:'auto',marginBottom:'auto',alignItems:'flex-end'}}>
                     <Icon name='md-close' fontSize="35"></Icon>
              </Button> */}
        </View>
        
        <Form>
            <Text>
              Message needs your phone number confirmation.Please conform your valid mobile number!
            </Text>
            <Item floatingLabel style={[styles.FloatingLabel]}>
                    <Label> </Label>
            <Input
                returnKeyType="next"
                clearButtonMode="always"
                autoCapitalize="none"
                autoCorrect={false}
                value={this.state.CustomerPhone}
                onChangeText={(text) => {
                                 
                 this.setState({CustomerPhone:text,AlternativeNumber:text})}}
                Style={{Color:'#5b5a5a'}} />
          </Item>

       <Item style={{borderColor: 'transparent',marginTop:10,marginLeft:'auto'}} >

            <Button onPress={()=>{this.SendSMS(this.state.transaction_id)}}>
              <Text>
                  Conform
              </Text>
            </Button>

            <Button light style={{marginLeft:8}}  onPress={()=>{this.setState({ConformPopup:!this.state.ConformPopup})}}>
              <Text>
                Cancel
              </Text>
            </Button>

    </Item>
</Form>
</Content>
</View>

</Modal>

);
  }

  /**
   * On change event to get the Job Status
   */


  _ChangeHandler_Job(value: string) {
    let SelectedValue=(value)?value:'';
       
   
    this.setState({
      JobStatus: SelectedValue,
     
    });

  }
    /**
   * On change event to get the Vehicle Details
   */

  _ChangeHandler_Vehicle(value: string) {
    let SelectedValue=(value)?value:'';
       
   
    this.setState({
      VehicleId: SelectedValue,
     
    });
    console.log(this.state.PrevState,SelectedValue);
  // return SelectedValue
   //loadData =async (this.state.personId,SelectedValue);
  }

  loadJobcardData =async (transaction_id,vehicle_id)  => {
      
        const ApiToken=await  AsyncStorage.getItem('Token', (err, item) => item);
        const org_id=await  AsyncStorage.getItem('org_id', (err, item) => item);
      
        console.log(`${API_URL}/jobcard_edit/${transaction_id}/${vehicle_id}/${org_id}`);
     
        axios.get(`${API_URL}/jobcard_edit/${transaction_id}/${vehicle_id}/${org_id}`, {
               method: 'GET',
               headers: {
                Accept: "application/json",
                   'Authorization':'Bearer '+ApiToken,
               },
             })
        .then(response => {
                  // If request is good...
              
                 
             console.log(response.data);
              //return false;
              
              if(response.status==200)
              {
                let ResponseData=response.data;
              // console.log(ResponseData.data.wms_attachments_progress);
         
               const CheckListData= ResponseData.data.checkbox_list;
                
                CheckListData.map((prop, key) =>{
                 
                    let comments=(prop.checklist_notes)?prop.checklist_notes:"";
                    
                    this.setState({
                      CheckListComments:this.state.CheckListComments.concat(comments)
                    });

                    if(prop.checklist_status)
                    {
                        this.setState({
                          CheckedListData:this.state.CheckedListData.concat(prop.checklist_id),
                          CheckedListKey:this.state.CheckedListKey.concat(key)
                        });
                    }

               });
              
               
                //return false;
                this.setState({
                  VehicleName:ResponseData.data.vehicle_configuration,
                  VehicleList:ResponseData.data.vehicles_register,
                  JobCardNumber:ResponseData.data.order_no,
                  VehicleCheckList:ResponseData.data.checkbox_list,
                  VehicleComplaints:ResponseData.data.vehicle_complaints,
                  VehicleId:parseInt(ResponseData.data.registration_id),
                  PrevState:ResponseData.data.registration_id,
                  Customer:ResponseData.data.name,
                  CustomerId:ResponseData.data.people_id,
                  CustomerPhone:ResponseData.data.mobile,
                  CustomerEmail:ResponseData.data.email,
                  Customer_address:ResponseData.data.address,
                  JobCardDate:this.formatDate(ResponseData.data.job_date),
                  JobCardDueDate:this.formatDate(ResponseData.data.job_due_date),
                  loading:false,
                  organization_id:org_id,
                  user_type:ResponseData.data.user_type,
                  Images_Ins_uploaded:ResponseData.data.wms_attachments_before,
                  Images_IP_uploaded:ResponseData.data.wms_attachments_progress,
                  Images_RD_uploaded:ResponseData.data.wms_attachments_after,
                  transaction_id:transaction_id,
                  JobStatuses:ResponseData.data.JobStatuses,
                  JobStatus:parseInt(ResponseData.data.jobcard_status_id),
                  JobcardItems:ResponseData.data.JobCardItemsList,
           //       SelectedJobItemList:ResponseData.data.JobItems,
                  Token:ApiToken,
                  DefalutItemStatus:ResponseData.defalut_item_status,
                  JobItemStatuses:ResponseData.jobitem_statuses,
                  SelectedJobItemData:ResponseData.data.JobItems

                  });

                //  console.log(ResponseData.data.JobStatuses,parseInt(ResponseData.data.jobcard_status_id));
           
              }

            })
        .catch((error) => {
                console.log('error ' + error);
                this.setState({loading:false});
          });

 
  };
   
  
  
  _findItem(query) {
    if (query === '') {
      return [];
    }
    
    const { JobcardItems } = this.state;
   
  
    if(query)
    {
      const regex = new RegExp(`${query.trim()}`, 'i');
      return JobcardItems.filter(item => item.name.search(regex) >= 0);

    }
  }


  getRegisteredVehicleDataById= async( VehicleId)=>{

  //  console.log(VehicleId);
    if(!VehicleId)
    {
      this.setState({
        VehicleName:'',
       
        Customer:'',
        CustomerPhone:'',
        PrevState:'',
        loading:false
        });
     
      return false;
    }
    const ApiToken=await  AsyncStorage.getItem('Token', (err, item) => item);
    const Org_ID=await  AsyncStorage.getItem('org_id', (err, item) => item);
    
    //console.log(`${API_URL}/wms_getVehicleDatas/${VehicleId}/${Org_ID}`);
    
    axios.get(`${API_URL}/wms_getVehicleDatas/${VehicleId}/${Org_ID}`, {
           method: 'GET',
           headers: {
            Accept: "application/json",
               'Authorization':'Bearer '+ApiToken,
           },
         })
    .then(response => {
              // If request is good...
          let ResponseData=response.data;
           console.log(ResponseData);
          if(ResponseData.status==1)
          {
            console.log(ResponseData);
            //return false;
            this.setState({
                  VehicleName:ResponseData.data.name.vehicle_configuration,
                  CustomerId:ResponseData.data.owner_id,
                  Customer:ResponseData.data.people.display_name,
                  CustomerPhone:ResponseData.data.people.mobile_no,
                  CustomerEmail:ResponseData.data.people.email_address,
                  PrevState:VehicleId,
                  user_type:ResponseData.data.user_type,
                  loading:false,
                  Customer_address:ResponseData.data.people.billing_city+ " <br /> "+ResponseData.data.people.billing_state,
              });
           


          }else{
            
          }

        })
    .catch((error) => {
            console.log('error ' + error);
      });



  }


  componentDidMount()
  {
    const {state} = this.props.navigation;
    const transaction_id=state.params.id;
    const vehicle_id=state.params.vehicle_id;
  //  return false;

      const Token = AsyncStorage.getItem('Token');
      if(Token){

          AsyncStorage.getItem('PersonId').then(PersonId => {
    
        //  this.setState({Person_id:UserData.id});
       
          let FetchData=this.loadJobcardData(transaction_id,vehicle_id);
         // console.log(PersonId);
          //console.log(this.state.JobCardDate);
          this.setState({Person_id:PersonId});
          //,JobCardDate:this.formatDate(this.state.JobCardDate),JobCardDueDate:this.formatDate(this.state.JobCardDueDate)

        });

  
    }
  }

  componentDidUpdate()
  {
      if(this.state.PrevState!=this.state.VehicleId)
        {
            //console.log('Working');
            this.getRegisteredVehicleDataById(this.state.VehicleId);
            
        }
  
  }



  SendSMS=(id)=>{

    console.log(id);
   // return false;
   
    const { organization_id,Token,AlternativeNumber }=this.state; 

   
   axios.post(`${API_URL}/jobcard_sms/${organization_id}`,{
     
      id:id,
      type:'job_card',
      mobile_no:AlternativeNumber
     }, {
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        'Authorization':'Bearer '+Token,
      }
      
    }).then(response =>{
      
      
   //   console.log(response.data.jobInvoice+  "Response Data");
      
  //    return false;
      
      
      if(response.status==200){
        
        toastr.showToast("SMS has been send successfully",2000);

        this.setState({ConformPopup:!this.state.ConformPopup})
          //  console.log(response.data.jobInvoice);
          }

     })
      .catch((error) => {
        console.error(error);
      });

  }

  DisplayFormatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('/');
  }


  formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
  }

  UploadImage= (type:string)=>{

    ImagePicker.launchCamera(options, (response) => {
      //console.log('Response = ', response);
  
    
      if (response.didCancel) {
        console.log('User cancelled image picker');
      }
      else if (response.error) {
        console.log('Image Picker Error: ', response.error);
      }
  
      else {

        let source = { uri: response.uri };
        let dataSource =response.data;
        let ImageName = response.fileName; 
      
        if(type===1)
        {
            this.setState({
            Images_Ins:this.state.Images_Ins.concat(source),
            ImageName_Ins:this.state.ImageName_Ins.concat(ImageName),
            ImageData_Ins:this.state.ImageData_Ins.concat(dataSource),
            });
        }
        
        if(type===2)
        {
            this.setState({
            Images_IP:this.state.Images_IP.concat(source),
            ImageName_IP:this.state.ImageName_IP.concat(ImageName),
            ImageData_IP:this.state.ImageData_IP.concat(dataSource),
            });
        }

        if(type===3)
        {
            this.setState({
            Images_RD:this.state.Images_RD.concat(source),
            ImageName_RD:this.state.ImageName_RD.concat(ImageName),
            ImageData_RD:this.state.ImageData_RD.concat(dataSource),
            });
        }
  
      
      
      }
    //  console.log(this.state.Images);
    });
}

onRemoveImage = (i,type) => {
  
        let ImageState;
        if(type===1)
        {
          ImageState="Images_Ins";
        }
        if(type===2)
        {
          ImageState="Images_IP";
        }
        if(type===3)
        {
          ImageState="Images_RD";
        };
      
        let filteredArray = this.state[ImageState].filter((item,J )=> i !== J)
        this.setState({[ImageState]: filteredArray});

  
};


_RemoveItem=(i)=>{

  //console.log(i);

  //let filteredArray = this.state.SelectedJobItemList.filter((item,J )=> i !== item);
  let filterQuantity = this.state.SelectedJobItemData.filter((item,j)=>i !== item.id);
  
  console.log(filterQuantity);

  this.setState({SelectedJobItemData: filterQuantity});

};

_DE_Quan=(id)=>{

  const Quantity=this.state.SelectedJobItemData;
  
  Quantity.filter(function(e) { 
   
    if(e.id === id){

      if(e.quantity===1)
      {
        return;
      }

      e.quantity=e.quantity-1;
    } 
     
  });

  this.setState({SelectedJobItemData:Quantity});

}

_IN_Quan=(id)=>{

  const Quantity=this.state.SelectedJobItemData;
 
  
  
  Quantity.filter(function(e) { 
   
    if(e.id === id){
      e.quantity=e.quantity+1;
    } 
    
  });
    
  this.setState({SelectedJobItemData:Quantity});

}

getItemData=(id,data_type)=>{

  const ItemData=this.state.SelectedJobItemData;
 
  if(ItemData.length==0)
  {
    return 1;
  }
  
  let obj = _.find(ItemData, function (obj) { return obj.id === id; });
 
 // console.log(obj.quantity);
 
  return obj[data_type] ;
 
}

updateItemData=(id,value)=>{
  console.log(id,value);
  const ItemData=this.state.SelectedJobItemData;
 
  
  
  ItemData.filter(function(e) { 
   
    if(e.id === id){
      e.item_status=value;
    } 
    
  });
  
  console.log(ItemData);

  this.setState({SelectedJobItemData:ItemData});

 
}

AddItemData=(id)=>{

  const ItemData=this.state.SelectedJobItemData;
 
  const DefalutItemStatus=this.state.DefalutItemStatus;
 
  console.log(ItemData.filter(e => e.id === id));


  if(ItemData.filter(e => e.id === id).length==0)
  {
   this.setState({SelectedJobItemData:this.state.SelectedJobItemData.concat([{id: id, quantity: 1,item_status:DefalutItemStatus}])}); 

  }


}
getStatus=(id,)=>{

  const Quantity=this.state.SelectedJobItemData;
 
  if(Quantity.length==0)
  {
    return 1;
  }
  
  let obj = _.find(Quantity, function (obj) { return obj.id === id; });
 
 // console.log(obj.quantity);
 
  return obj.quantity ;
 
}

  _SubmitHandler = async() =>
  {
  
    
    const ApiToken=await  AsyncStorage.getItem('Token', (err, item) => item);
    
    const {
      CustomerId,CustomerEmail,JobCardDate,JobCardDueDate,CustomerPhone,
      type,Customer,VehicleComplaints,VehicleId,VehicleName,JobCardNumber, 
      Images_Ins,ImageData_Ins,ImageName_Ins,
      Images_IP,ImageData_IP,ImageName_IP,
      Images_RD,ImageData_RD,ImageName_RD,
      CheckedListData,CheckListComments,
      CheckedListKey,
      Person_id,
      user_type,
      Customer_address,
      organization_id,
      JobStatus,
    //  SelectedJobItemList
    SelectedJobItemData,
    } = this.state;
    const data = [];
 //   console.log("Working");

    const CheckListData=[];
    
    CheckedListData.map((prop, key) => {

        
        let CommentKey=CheckedListKey[key];
     
        CheckListData.push({CheckList_id:prop,CheckList_Comments:CheckListComments[CommentKey]});


    });
     
     
    const  CheckListFormData={ name : 'checkListData', data :JSON.stringify( CheckListData)};
    
    
    data.push(CheckListFormData);

    let FormDataObj = {
      id:this.state.transaction_id?this.state.transaction_id:null,                    
      type_id:type,

      job_card_no:JobCardNumber,
      
//        JobCardItems:SelectedJobItemList,
      jobItemData:SelectedJobItemData,
      registration_id:VehicleId,
      customer_type:user_type,
      people_id:CustomerId,
      complaint:VehicleComplaints,
      customer:Customer,
      customer_mobile_number:CustomerPhone,
      customer_email:CustomerEmail,
      job_date:JobCardDate,
      job_due_date:JobCardDueDate,
      organization_id:organization_id,
      CheckListData:JSON.stringify(CheckListData),
      Person_id:Person_id,
      customer_address:Customer_address,
      jobcard_status_id:JobStatus
      };

    const formData={ name : 'data',  data : JSON.stringify(FormDataObj)
              };
              data.push(formData);

        
              Images_Ins.map((prop, key) => {

                Img={
                   name:'images_inspected[]',
                   filename:ImageName_Ins[key],
                   data:ImageData_Ins[key]
                 };
                 data.push(Img);

            });

            Images_IP.map((prop, key) => {

              Img={
                 name:'images_progress[]',
                 filename:ImageName_IP[key],
            
                 data:ImageData_IP[key]
               };
               data.push(Img);

          });

          Images_RD.map((prop, key) => {

            Img={
               name:'images_ready[]',
               filename:ImageName_RD[key],
           
               data:ImageData_RD[key]
             };
             data.push(Img);

        });

        

        
          for(var propertyName in FormDataObj) {
            // propertyName is what you want
            // you can get the value like this: myObject[propertyName]
          //  if(){
              if(propertyName=="registration_id" && !FormDataObj[propertyName])
              {
                toastr.showToast('Please Select Registration Number',1500);
                return;
               
              }
          //  }
         }
        
         
        
        

              // if(Object.keys(SelectedJobItemData).length==0){
              // //  alert('Please Select Job card Items');
              //   toastr.showToast('Please Select Job  Items',1500);
              //   return;
               
              // }
          
              this.setState({loadingSpinner:true});


              RNFetchBlob.fetch('POST', `${API_URL}/wms_jobcard/${this.state.transaction_id}`, {
         
                Authorization : 'Bearer '+ApiToken,
              //  otherHeader : "foo",
                'Content-Type' : 'multipart/form-data',
                
              }, data).then((resp) => {
                            
                            // if (resp.headers.get('content-type').match(/application\/json/)) {
                            //   return resp.json();
                            // }
                            console.log(resp);
                            this.setState({loadingSpinner:false});
                        
                            if(resp.data)
                           {
                            const Response=JSON.parse(resp.data);
                         
                               
                            if(Response.message == "SUCCESS")
                            {
                              this.setState({'JobCardNumber':Response.data.orderNo,'transaction_id':Response.data.id})

                              toastr.showToast("Changes Updated successfully",2000);


                             this.props.navigation.push("JobCardLst");
                            }else{
                              toastr.showToast("Something Went to Wrong",2000);

                            }
                           }
                        
                  }).catch((err) => {
                   console.log(err);
                  })

      

  
    //  alert(JobCardNumber);
  }

  UploadImage=(type:string)=>{

      ImagePicker.launchCamera(options, (response) => {
        //console.log('Response = ', response);
    
       
        if (response.didCancel) {
          console.log('User cancelled image picker');
        }
        else if (response.error) {
          console.log('Image Picker Error: ', response.error);
        }
    
        else {
  
          let source = { uri: response.uri };
          let dataSource =response.data;
          let ImageName = response.fileName; 
        
          if(type===1)
          {
              this.setState({
              Images_Ins:this.state.Images_Ins.concat(source),
              ImageName_Ins:this.state.ImageName_Ins.concat(ImageName),
              ImageData_Ins:this.state.ImageData_Ins.concat(dataSource),
              });
          }
          
          if(type===2)
          {
              this.setState({
              Images_IP:this.state.Images_IP.concat(source),
              ImageName_IP:this.state.ImageName_IP.concat(ImageName),
              ImageData_IP:this.state.ImageData_IP.concat(dataSource),
              });
          }

          if(type===3)
          {
              this.setState({
              Images_RD:this.state.Images_RD.concat(source),
              ImageName_RD:this.state.ImageName_RD.concat(ImageName),
              ImageData_RD:this.state.ImageData_RD.concat(dataSource),
              });
          }
     
        
         
        }
      //  console.log(this.state.Images);
      });
  }
  _RemoveItem=(i)=>{

    console.log(i);
    let filteredArray = this.state.SelectedJobItemData.filter((item,J )=> i !== item.id)
    console.log(filteredArray)
    this.setState({SelectedJobItemData: filteredArray});

  };

  onUpdateComments = (i,value) => {
    const commentsArray=this.state.CheckListComments;
    commentsArray[i]=value;
    this.setState({CheckListComments:commentsArray});

  };

    onRemoveImage = (i,type) => {
      
            let ImageState;
            if(type===1)
            {
              ImageState="Images_Ins";
            }
            if(type===2)
            {
              ImageState="Images_IP";
            }
            if(type===3)
            {
              ImageState="Images_RD";
            };
          
            let filteredArray = this.state[ImageState].filter((item,J )=> i !== J)
            this.setState({[ImageState]: filteredArray});

      
    };

    onUpdateComments = (i,value) => {
      const commentsArray=this.state.CheckListComments;
      commentsArray[i]=value;
      console.log(value);
      this.setState({CheckListComments:commentsArray});

    };



    toggleCheckBox=(value,key)=>{

      let data=this.state.CheckedListData;

     const IsExist= _.includes(data,value)
  
      if(IsExist)
      {
        let CheckedListArray = this.state.CheckedListData.filter((item,J )=> value !== item)
        let CheckedListKey = this.state.CheckedListKey.filter((item,J)=>key!==item)
        this.setState({CheckedListData: CheckedListArray,CheckedListKey:CheckedListKey});
       
        
      }else{
        this.setState({
          CheckedListData:this.state.CheckedListData.concat(value),
          CheckedListKey:this.state.CheckedListKey.concat(key)
      })
    }
  //  console.log(this.state.CheckedListKey);
    }



    _renderHeader({title, expanded}) {
      
   
                let IconName;
                if(title==='Job Header')
                {
                  IconName=`card`;
                }else
                if(title==='Photos')
                {
                  IconName=`camera`;
                }else
                if(title==='Job Items')
                {
                  IconName=`clipboard`;
                }else{
                
                  IconName=`list-box`;;
                }

                return (
                  <View
                    style={{ flexDirection: "row", padding: 10, justifyContent: "space-between", alignItems: "center", backgroundColor: "#feefc3",borderBottomWidth:1,borderColor:'#f4e8b7' }}
                  >
                    <Icon name={IconName}/>      
                    <Text style={{ fontWeight: "bold", fontSize:20 }}>
                      {" "}{title}
                    </Text>
                    {expanded
                      ? <Icon style={{ fontSize: 18 }} name="arrow-dropup-circle" />
                      : <Icon style={{ fontSize: 18 }} name="arrow-dropdown-circle" />}
                  </View>
                );
    }
  
  _renderContent(section) {
    
        const Heading=section.title;
        const Images_Ins=this.state.Images_Ins;

        const Images_Ins_uploaded=this.state.Images_Ins_uploaded;
        const Images_Ins_up_length=Images_Ins_uploaded.length;


        const TotalImages_Ins=this.state.Images_Ins.filter(m => m.uri);
        const Images_InsLength=TotalImages_Ins.length;
        

        const Images_IP=this.state.Images_IP;
       

        const TotalImages_IP=this.state.Images_IP.filter(m => m.uri);
        const Images_IPLength=TotalImages_IP.length;
        const Images_IP_uploaded=this.state.Images_IP_uploaded;
        const Images_IP_up_length=Images_IP_uploaded.length;



        const Images_RD=this.state.Images_RD;
        const TotalImages_RD=this.state.Images_RD.filter(m => m.uri);
        const Images_RDLength=TotalImages_RD.length;
        const Images_RD_uploaded=this.state.Images_RD_uploaded;
        const Images_RD_up_length=Images_RD_uploaded.length;
     //   console.log("before",Images_Ins_uploaded,Images_Ins_up_length);
     //   console.log("progress",Images_IP_uploaded,Images_IP_up_length);
        
      //  console.log("ready",Images_RD_uploaded,Images_RD_up_length);

        const VehicleList =this.state.VehicleList;
        const CheckedListData=this.state.CheckedListData;
        const JobCardNumber=this.state.JobCardNumber;
        const VehicleCheckList=this.state.VehicleCheckList;
        const CheckListComments=this.state.CheckListComments;
        const DisplayJobCardDate=this.DisplayFormatDate(this.state.JobCardDate);
        const DisplayJobCardDueDate=this.DisplayFormatDate(this.state.JobCardDueDate);
        const JobcardStatusList=this.state.JobStatuses;
        const CurrentJobStatus=this.state.JobStatus;
        const SearchItemQuery=this.state.query;
        const JobcardItems=this.state.JobcardItems;
        const SelectedJobItemData=this.state.SelectedJobItemData;
     //   const SelectedJobItemList=this.state.SelectedJobItemList;

        const JobItemStatusList=this.state.JobItemStatuses;
        const DefalutItemStatus=this.state.DefalutItemStatus;
        

         console.log(DefalutItemStatus);
         console.log(SelectedJobItemData);
    //     console.log(SelectedJobItemList);
         console.log(JobcardItems);
         
         const SearchItemList = this._findItem(SearchItemQuery);

         const comp = (a, b) => a.toLowerCase().trim() === b.toLowerCase().trim();
         let S_no=0;


        let JobCard=(
     
         
          <View style={[styles.Container]}>
          <ScrollView contentContainerStyle={{flexGrow:1}} >
            
                  <View
                  style={{
                    borderWidth: 1,
                    borderColor: '#e8e8e8',
                    borderRadius:3,
                    marginBottom:12,
                    }}
                  />
                      <Form>
                            <Label style={[styles.Label]} >JobCard Number</Label>
                            <Item regular style={[styles.InputItem]}>
                                  <Input    style={[styles.Input,{height: Math.max(37, this.state.height)}]}  
                                    placeholder="Enter The Job Card Number"
                                    numberOfLines={1} 
                                    disabled={true}
                                    onChangeText={(value) => this.setState({'JobCardNumber':value})} value={this.state.JobCardNumber}

                              />

                           
                            </Item>

                            <Label style={[styles.Label]} >Vehicle Number</Label>
                            <Item regular style={[styles.InputItem]}>
                                  {/* <Input    style={[styles.Input,{height: Math.max(37, this.state.height)}]}  
                                   placeholder="Enter The Vehicle Name"
                                    /> */}
                                    <Picker
                                      mode="dropdown"
                                      Icon={<Icon name="ios-arrow-down" />}
                                      style={{ width: 100,color:'0d0d0dc7',backgroundColor:'white' }}
                                      placeholder="Select your SIM"
                                      placeholderStyle={{ color: "#bfc6ea" }}
                                      placeholderIconColor="#007aff"
                                    
                                      itemStyle={{
                                              backgroundColor: "white",
                                      
                                      }}
                                      itemTextStyle={{ color: "#788ad2" }}
                                      selectedValue={this.state.VehicleId}
                                      onValueChange={this._ChangeHandler_Vehicle.bind(this)}
                                    
                              
                                              >
                                              <Item label="SELECT" value="" />
                                                {
                                                  //const VehicleList=this.state.VehicleList;
                                                  VehicleList.map((prop, key) => {
                                                  return (
                                                          <Item label={prop.registration_no} value={prop.id}   itemStyle={{color:'#F97C2C'}}/>
                                                        
                                                          );
                                                      })
                                                }
                                      </Picker>
                            </Item>

                            
                            <Label style={[styles.Label]} >Vehicle Name</Label>
                            <Item regular style={[styles.InputItemReadOnly]}>
                                  <Input    style={[styles.Input,{height: Math.max(37, this.state.height)}]}  
                                   placeholder="Enter The Vehicle Name"
                                   value={this.state.VehicleName}
                                   disabled={true}
                                  />
                            </Item>
                            
                            <Label style={[styles.Label]} >Complaints</Label>
                            <Item regular style={[styles.InputItem]}>
                                  {/* <Input    style={[styles.Input,{height: Math.max(35, this.state.height)}]}  
                                  /> */}
                                  <Textarea   placeholder="Enter The Vehicle Complaints"  
                                      onChangeText={(value) => this.setState({'VehicleComplaints':value})} 
                                      value={this.state.VehicleComplaints}
                                   />
                            </Item>

                            <Label style={[styles.Label]} >Customer</Label>
                            <Item regular style={[styles.InputItemReadOnly]}>
                                  <Input    style={[styles.Input,{height: Math.max(37, this.state.height)}]}  
                                       value={this.state.Customer}
                                       disabled={true}  
                                  />
                            </Item>

                            <Label style={[styles.Label]} >Customer Phone</Label>
                            <Item regular style={[styles.InputItemReadOnly]}>
                                  <Input style={[styles.Input,{height: Math.max(37, this.state.height)}]}     
                                    value={this.state.CustomerPhone}
                                    disabled={true}

                                   />
                            </Item>

                            <Label style={[styles.Label]} >Job card date</Label>
                            <Item regular style={[styles.InputItemReadOnly]}>
                                    <DatePicker
                                        defaultDate={new Date(DisplayJobCardDate)}
                                        minimumDate={new Date(2018, 1, 1)}
                                        maximumDate={new Date(2050, 12, 31)}
                                        locale={"en"}
                                        timeZoneOffsetInMinutes={undefined}
                                        modalTransparent={false}
                                        animationType={"fade"}
                                        androidMode={"default"}
                                        placeHolder={this.state.JobCardDate}
                                        textStyle={[styles.Label,{height: Math.max(37, this.state.height)}]}
                                        placeHolderTextStyle={{ color: "#d3d3d3", }}
                                        onDateChange={(value) => {
                                          this.setState({
                                            JobCardDate: this.formatDate(value), 
                                            
                                              });
                                        }}
                                       disabled={true}
                                      />
                            </Item>

                            <Label style={[styles.Label]} >Job Due date</Label>
                            <Item regular style={[styles.InputItem]}>
                                      <DatePicker
                                        defaultDate={new Date(DisplayJobCardDueDate)}
                                        minimumDate={new Date(2018, 1, 1)}
                                        maximumDate={new Date(2050, 12, 31)}
                                        locale={"en"}
                                        timeZoneOffsetInMinutes={undefined}
                                        modalTransparent={false}
                                        animationType={"fade"}
                                        androidMode={"default"}
                                        placeHolder={this.formatDate(this.state.JobCardDueDate)}
                                        textStyle={[styles.Label,{height: Math.max(37, this.state.height)}]}
                                        placeHolderTextStyle={{ color: "#d3d3d3", }}
                                        onDateChange={(value) => {
                                          this.setState({
                                            JobCardDueDate: this.formatDate(value), 
                                          
                                              });
                                        }}
                                      />
                            </Item>
                            <Label style={[styles.Label]} >Job Status</Label>
                            <Item regular style={[styles.InputItem]}>
                                  {/* <Input    style={[styles.Input,{height: Math.max(37, this.state.height)}]}  
                                   placeholder="Enter The Vehicle Name"
                                    /> */}
                                    <Picker
                                      mode="dropdown"
                                      Icon={<Icon name="ios-arrow-down" />}
                                      style={{ width: 100,color:'0d0d0dc7',backgroundColor:'white' }}
                                      placeholder="Select your SIM"
                                      placeholderStyle={{ color: "#bfc6ea" }}
                                      placeholderIconColor="#007aff"
                                    
                                      itemStyle={{
                                              backgroundColor: "white",
                                      
                                      }}
                                      itemTextStyle={{ color: "#788ad2" }}
                                      selectedValue={CurrentJobStatus}
                                      onValueChange={this._ChangeHandler_Job.bind(this)}
                                    
                              
                                              >
                                              <Item label="SELECT" value="" />
                                                {
                                                  //const VehicleList=this.state.VehicleList;
                                                  JobcardStatusList.map((prop, key) => {
                                                  return (
                                                          <Item label={prop.name} value={prop.id}   itemStyle={{color:'#F97C2C'}}/>
                                                        
                                                          );
                                                      })
                                                }
                                      </Picker>
                            </Item>

               

                           
                      </Form>
         
           
        </ScrollView>
      </View>
    );



    let Photos=(

      <View style={[styles.Container]}>
          <ScrollView contentContainerStyle={{flexGrow:1}} >
           
              {/* Start Inspected Image */}
                <Badge rounded disabled primary>
                <Text style={{color:'white',fontSize:14}} >Inspected  </Text>
                </Badge>
                <View style={[styles.ImageContainer_IN,styles.DisplayImage]}>

                    <ScrollView horizontal={true}>

                                           {Images_Ins_uploaded.map((prop, key) => {
                                                let imageSource = prop.image_url;
                                                console.log("Img?");
                                                console.log(imageSource);
                                            return (
                                              <View style={{margin:5}}>
                                                    <Image source={{uri:imageSource}} style={[styles.DisplayImage,(key===this.state.SelectImage)?styles.SelectedImage:'',{borderRadius:10,borderWidth:2,borderColor:'#929da9'}]}/>
                                                          <Button rounded small danger 
                                                                  style={[styles.ImageCloseButton]} 
                                                                  data-type="1"
                                                                  onPress={() => this.onRemoveImage(key,1)}> 
                                                          <Icon name="md-close" style={[styles.Icon,{color:'white'}]} />
                                                         
                                                      </Button>
                                              </View>
                                                          );
                        
                                            
                                                })}



                                          {Images_Ins.map((prop, key) => {
                                                let imageSource=Images_Ins[key].uri;
                                            return (
                                              <View style={{margin:5}}>
                                                    <Image source={{uri:imageSource}} style={[styles.DisplayImage,(key===this.state.SelectImage)?styles.SelectedImage:'',{borderRadius:10,borderWidth:2,borderColor:'#929da9'}]}/>
                                                          <Button rounded small danger 
                                                                  style={[styles.ImageCloseButton]} 
                                                                  data-type="1"
                                                                  onPress={() => this.onRemoveImage(key,1)}> 
                                                          <Icon name="md-close" style={[styles.Icon,{color:'white'}]} />
                                                         
                                                      </Button>
                                              </View>
                                                          );
                        
                                            
                                            })}


                                          
                                          

                                                  
              
                      </ScrollView>
                      <Button light block   onPress={() => this.UploadImage(1) } style={[{margin:5},(Images_InsLength||Images_Ins_up_length)?styles.IsExistImage:styles.IsNotExistImage]}>
                            <Text>
                                  <Icon name="camera"   />
                                  <Icon name="add"   />                         

                            </Text>         
                      </Button>
                </View>

                {/* END Inspected Images */}

                {/* START In Progress Image */}
                <Badge rounded disabled style={[{backgroundColor:'#F97C2C'}]}>
                <Text style={{color:'white',fontSize:14}} >In Progress  </Text>
                </Badge>
                <View style={[styles.ImageContainer_IP,styles.DisplayImage]}>

                    <ScrollView horizontal={true}>
                                          
                                        {Images_IP_uploaded.map((prop, key) => {
                                          let imageSource = prop.image_url;
                                            return (
                                              <View style={{margin:5}}>
                                                    <Image source={{uri:imageSource}} style={[styles.DisplayImage,(key===this.state.SelectImage)?styles.SelectedImage:'',{borderRadius:10,borderWidth:2,borderColor:'#929da9'}]}/>
                                                          <Button rounded small danger 
                                                                  style={[styles.ImageCloseButton]} 
                                                               > 
                                                                  <Icon name="md-close" style={[styles.Icon,{color:'white'}]} />
                                                         
                                                          </Button>
                                              </View>
                                                          );
                        
                                            
                                            })}
                                          
                                          
                                          
                                          
                                          {Images_IP.map((prop, key) => {
                                                let imageSource=Images_IP[key].uri;
                                            return (
                                              <View style={{margin:5}}>
                                                    <Image source={{uri:imageSource}} style={[styles.DisplayImage,(key===this.state.SelectImage)?styles.SelectedImage:'',{borderRadius:10,borderWidth:2,borderColor:'#929da9'}]}/>
                                                          <Button rounded small danger 
                                                                  style={[styles.ImageCloseButton]} 
                                                                  onPress={() => this.onRemoveImage(key,2)}> 
                                                                  <Icon name="md-close" style={[styles.Icon,{color:'white'}]} />
                                                         
                                                          </Button>
                                              </View>
                                                          );
                        
                                            
                                            })}
                                          

                                                  
              
                      </ScrollView>
                      <Button light block   onPress={() => this.UploadImage(2) }  style={[{margin:5},(Images_IPLength||Images_IP_up_length)?styles.IsExistImage:styles.IsNotExistImage]}>
                            <Text>
                                  <Icon name="camera"   />
                                  <Icon name="add"   />                         

                            </Text>         
                      </Button>
                </View> 
              {/* END In Progress Image */}
              

              {/* START In Ready Image */}
              <Badge rounded disabled success>
                <Text style={{color:'white',fontSize:14}} >Ready  </Text>
                </Badge>
                <View style={[styles.ImageContainer_RD,styles.DisplayImage]}>

                    <ScrollView horizontal={true}>
                                          
                                           {Images_RD_uploaded.map((prop, key) => {
                                            let imageSource = prop.image_url;
                                            return (
                                              <View style={{margin:5}}>
                                                    <Image source={{uri:imageSource}} style={[styles.DisplayImage,(key===this.state.SelectImage)?styles.SelectedImage:'',{borderRadius:10,borderWidth:2,borderColor:'#929da9'}]}/>
                                                          <Button rounded small danger 
                                                                  style={[styles.ImageCloseButton]} 
                                                                  > 
                                                          <Icon name="md-close" style={[styles.Icon,{color:'white'}]} />
                                                         
                                                      </Button>
                                              </View>
                                                          );
                        
                                            
                                            })}


                                          {Images_RD.map((prop, key) => {
                                                let imageSource=Images_RD[key].uri;
                                            return (
                                              <View style={{margin:5}}>
                                                    <Image source={{uri:imageSource}} style={[styles.DisplayImage,(key===this.state.SelectImage)?styles.SelectedImage:'',{borderRadius:10,borderWidth:2,borderColor:'#929da9'}]}/>
                                                          <Button rounded small danger 
                                                                  style={[styles.ImageCloseButton]} 
                                                                  onPress={() => this.onRemoveImage(key,3)}> 
                                                          <Icon name="md-close" style={[styles.Icon,{color:'white'}]} />
                                                         
                                                      </Button>
                                              </View>
                                                          );
                        
                                            
                                            })}
                                          

                                                  
              
                      </ScrollView>
                      <Button light block   onPress={() => this.UploadImage(3) } style={[{margin:5},(Images_RDLength||Images_RD_up_length)?styles.IsExistImage:styles.IsNotExistImage]}>
                            <Text>
                                  <Icon name="camera"   />
                                  <Icon name="add"   />                         

                            </Text>         
                      </Button>
                </View> 
              {/* END In Ready Image */}
                                   
              
              
              </ScrollView>    
    
      </View>
    );

    let CheckList=(
     
                  <ScrollView>

                        
                          


                              {
                                
                                
                                               VehicleCheckList.map((prop, key) => {
                                                const IsExist= _.includes(CheckedListData,prop.checklist_id);
                                             //   let  CheckListComments[key] = (prop.checklist_notes)?prop.checklist_notes:CheckListComments[key];
                                                    (CheckListComments[key]!="no comments")?prop.checklist_notes=CheckListComments[key]:prop.checklist_notes;
                                                   
                                                  return (

                                                        
                                                          
                                                                <ListItem button noBorder onPress={() => this.toggleCheckBox(prop.checklist_id,key)} >

                                                                            <Left style={{flex:0.1,marginBottom:'auto',marginTop:5}}>
                                                                                      <CheckBox 
                                                                                        onPress={() => this.toggleCheckBox(prop.checklist_id,key)}
                                                                                        checked={IsExist}
                                                                                      />
                                                                            </Left>
                                                                            
                                                                            <Body >
                                                                              <Text>{prop.name}</Text>
                                                                            
                                                                              <Item  style={{margin:5,height:80,backgroundColor:'#eff0f1',borderRadius:5}} >
                                                                                      <Input  style={{height:80,margin:0,color:CheckListComments[key]?'#495057':'#eff0f1'}}  
                                                                                              multiline={true}  
                                                                                              placeholder='Enter the Notes...'
                                                                                              value={ CheckListComments[key]}
                                                                                              fontStyle='italic'
                                                                                              onChangeText={(value) => this.onUpdateComments(key,value)}
                                                                                      />
                                                                              
                                                                              </Item>

                                                                            </Body>
                                                                        
                                                                </ListItem>
                                                              

                                                                            
                                                               
                                                     
                                                         
                                                          );
                                                      })
                               }
                               {/* <Grid style={{alignItems: 'center',marginBottom:10}}>
                                    <Col >
                                        <Button primary rounded style={{marginLeft:'auto',marginRight:'auto'}} onPress={ this._SubmitHandler}>
                                          <Text>Save JobCard</Text>
                                        </Button>
                                    </Col>
                              </Grid> */}
                              </ScrollView>
                             
                );

    let Jobcard_Items=(
            <View style={[styles.Container]}>
                
                <ScrollView style={{padding:10}}>

                      <View style={styles.Searchcontainer}>
                              <Title style={{textAlign:'left',color:'#0d0d0dc7'}}>Job & Parts</Title> 
                              <Subtitle style={{textAlign:'left',color:'#0d0d0dc7',marginBottom:4}}>Search & Select Job Items</Subtitle> 
                          
                              <View style={{flexDirection:'column'}}>       
                                  <Autocomplete 
                                    autoCapitalize="none"
                                    autoCorrect={false}
                                    inputContainerStyle={{backgroundColor: '#ffffff'}}
                                    containerStyle={styles.autocompleteContainer}
                                    renderTextInput={this._renderInput}
                                    data={SearchItemList.length === 1 && comp(SearchItemQuery, SearchItemList[0].name) ? [] : SearchItemList}
                                    defaultValue={null}
                                    onChangeText={text => this.setState({ query: text })}
                                    value={this.state.query}
                                    underlineColorAndroid='rgba(0,0,0,0)'
                                    keyboardShouldPersistTaps='always'
                                    placeholder="Search the Item"
                                    listStyle={{minHeight:100}}
                                    style={{
                                      backgroundColor: '#ffffff',
                                      borderRadius: 10,
                                      height: 36,
                                      fontSize: 17,
                                      padding: 7,
                                      borderWidth:0,
                                    }}
                                    renderItem={({ item }) => (
                                      <TouchableOpacity onPress={() => this.setState({ query: item.name},this.AddItemData(item.id),console.log(this.state.SelectedJobItemData))}  style={{height:25,overflow:'hidden',margin:8}}>
                                        <Text style={styles.itemText}>
                                          {item.name} 
                                        </Text>
                                      </TouchableOpacity>
                                    )}
                                  
                                  />

                                {this.state.query? (<Button small transparent  style={{position:'absolute',right:0,top:10}} onPress={() => {this.setState({query:''})}}>
                                        <Text>
                                                  <Icon name="md-close" style={{fontSize:30}}/> 
                                        </Text>
                                  </Button>):(null)}
                              </View>
                          
                            </View>
                        
                              

                            {JobcardItems.map((prop, key) => {
                                            //  let IsExist= _.includes(SelectedJobItemList,prop.id);
                                              let IsExist=(SelectedJobItemData.filter(e => e.id === prop.id).length > 0)?true:false;
                                           
                                              let quantity=(IsExist)?this.getItemData(prop.id,"quantity"):1;
                                              
                                              let item_status=(IsExist)?this.getItemData(prop.id,"item_status"):DefalutItemStatus;
                                               
                                              (IsExist)?S_no++:null;

                                              let returnData=(IsExist)? (
                                                
                                                          <Card padder style={[styles.Card]}>
                                                                
                                                                <CardItem bordered style={[styles.Card]}>
                                                                  <Left style={{flex:0.3}}>
                                                                              <Icon name="md-checkmark-circle-outline" style={{color:"#3880ff",padding:4,backgroundColor:"#cddcf7",borderRadius:40}}/>    


                                                                  </Left>
                                                                  <Body>
                                                                    <Text  style={{fontWeight: "bold"}}>
                                                                      {S_no+"."+prop.name}
                                                                    </Text>
                                                                    <Text note numberOfLines={1}>{prop.category}</Text>
                                                                  </Body>
                                                                  <Right>
                                                                    <Button transparent onPress={()=>this._RemoveItem(prop.id)}>
                                                                        <Icon name="md-close" style={{fontSize:30}} />    
                                                                    </Button>

                                                                  </Right>
                                                                </CardItem>
                                                                <CardItem  style={[styles.Card,{margin:'auto'}]}>
                                                                          {/* <Text  style={{fontWeight: "bold"}}>
                                                                            Quantity
                                                                          </Text> */}
                                                                          <Button small style={{backgroundColor:'#ecf0f6',marginLeft:10,marginTop:10,marginRight:10}} onPress={()=>this._DE_Quan(prop.id)}>
                                                                            <Icon name="md-remove" style={{color:'black'}} />
                                                                          </Button>
                                                                          <Text  style={{fontWeight: "bold"}}>
                                                                          {quantity}
                                                                          </Text>
                                                                          <Button small style={{backgroundColor:'#ecf0f6',marginLeft:10,marginTop:10,marginRight:10}} onPress={()=>this._IN_Quan(prop.id)}>
                                                                            <Icon name="md-add"  style={{color:'black'}}/>
                                                                          </Button>
                                                                          <Picker
                                                                                mode="dropdown"
                                                                                Icon={<Icon name="ios-arrow-down" />}
                                                                                style={{ width: 10,color:'0d0d0dc7',backgroundColor:'#ecf0f6' ,padding:0}}
                                                                                placeholder="Select your SIM"
                                                                                placeholderStyle={{ color: "#bfc6ea" }}
                                                                                placeholderIconColor="#007aff"
                                                                                
                                                                                itemStyle={{
                                                                                        backgroundColor: "white",
                                                                                        width: 10
                                                                                
                                                                                }}
                                                                                itemTextStyle={{ color: "#788ad2" }}
                                                                                selectedValue={item_status}
                                                                                onValueChange={(val)=>this.updateItemData(prop.id,val)}
                                                                              
                                                                        
                                                                                        >
                                                                                        <Item label="Select Status" value="" />
                                                                                          {
                                                                                            //const VehicleList=this.state.VehicleList;
                                                                                            JobItemStatusList.map((prop, key) => {
                                                                                            return (
                                                                                                    <Item label={prop.name} value={prop.id}   itemStyle={{color:'#F97C2C'}}/>
                                                                                                  
                                                                                                    );
                                                                                                })
                                                                                          }
                                                                                </Picker>
                                                                    </CardItem>
                                                                
                                                          </Card>):(null);
                                                                        
                                                              
                                                      return returnData;
                                                        
                                                        
                                                    })
                              }
                              
                            </ScrollView>
                </View>           
              );
    


    let content;
    if(Heading==='Job Header')
    {
      content=JobCard;
    }else
    if(Heading==='Photos')
    {
      content=Photos;
    }else
    if(Heading==='CheckList'){
     
      content=CheckList;
    }else{
      
      content=Jobcard_Items;
    }
    
    
    return (

     <View>{content}</View>
      
    );
  }

    
    
    
    

  render() {
    const { loading,submissionLoading,loadingSpinner }= this.state;

    if(submissionLoading)
    {
      <Container>
      <Content padder>
      <View style={styles.loading}>
      <Loader   />
    </View>
    </Content>
    </Container>
    }
    if(!loading) { 
      return (
      <Container>
        
          <Content padder>
          <View style={styles.loading}>
          <Spinner   visible={loadingSpinner}  textContent={'Save processing...'}/>
        </View>
        <ScrollView>
                                        {this.ConfirmationPopup()} 
                  <Accordion dataArray={ScreensHeading} expanded={0}  renderHeader={this._renderHeader}   renderContent={this._renderContent.bind(this)}/>
                  <Grid style={{alignItems: 'flex-start',marginBottom:10,marginTop:10}}>
                                  {/* <Col >
                                      <Button primary rounded style={{marginLeft:'auto',marginRight:'auto'}} onPress={ ()=>{this.setState({ConformPopup:!this.state.ConformPopup},console.log(this.state.ConformPopup))}} >
                                        <Text>Send SMS</Text>
                                      </Button>
                                  </Col> */}
                                  <Col >
                                      <Button primary rounded style={{marginLeft:'auto',marginRight:'auto'}} onPress={ this._SubmitHandler} >
                                        <Text>Save JobCard</Text>
                                      </Button>
                                  </Col>
                            </Grid>
          
          
        </ScrollView>

          </Content>

          


      </Container>

    );
    }else{
        return (
                  <Content contentContainerStyle={{flex: 1}} style={{padding: 10}}>
                    <Grid style={{alignItems: 'center'}}>
                      <Col>
                          <Spinner  visible={loading}/>
                      </Col>
                    </Grid>
                  </Content>
        );
    }
    
  }
  }


  const styles = StyleSheet.create({
    Searchcontainer: {
 
      flex: 1,
      padding: 16,
      marginTop: 2,
    },
    noBorder: {
     borderBottomWidth:0
    },
    autocompleteContainer:{
      backgroundColor: '#ffffff',
      borderWidth: 0,
    },

    colWidth: {
      flex:1
     },
     actionButtonIcon: {
      fontSize: 20,
      height: 22,
      color: 'white',
    },
    Label:{
        color:'#0d0d0dc7',
        fontSize:14
    },
    Title:{
      color:'#0d0d0d',
      marginBottom:3
      
    },
    Label_secondary:{
      color:'#0d0d0dc7',
      paddingTop:10,
      fontSize:14
    },
    InputItem:{
        borderWidth:0,
        borderRadius:2,
        backgroundColor:'white',
        overflow:'hidden',
        marginBottom:10,
    },
    DisplayImage:{
      flex: 1,
      margin:10,
      minWidth:150,
      aspectRatio: 1.5,
     
    },
    HiddenImage:{
      width:0,
      minHeight:0,
      marginTop:10,
      alignSelf:'center'
    },
    ImageButton:{
      
      marginBottom:30,
    },
    SelectedImage:{
      borderColor:"#F97C2C",
      borderWidth: 2,
    },
    ErrorInput:{
      color:'red',
      fontSize:13
    },
    Input:{
       fontSize:14
    },
    RadioInput:{
      fontSize:14
    },
    Icon: {
      color:'#929da9',
      fontSize: normalize(12),

    },
    Container:{
      backgroundColor:'#f6f4f6',
      flex:1,
      flexGrow:1
    },
    ImageViewHidden:{
      minHeight:0
    },
    ImageViewDisplay:{
      minHeight:100
    },
    IsNotExistImage:{
      borderRadius:10,
      width:'100%',
      height:'90%'
    },
    IsExistImage:{
      borderRadius:10,
      width:'100%',
      height:'30%'
    },
    ImageCloseButton:{
      position: 'absolute',
      right: 0,
      top: 0,
      bottom: 0,
      alignItems:'center',
    },
    ImageContainer_IN:{
      borderLeftColor:'#3F51B5',
      borderLeftWidth:2,
      marginLeft:20,
      marginTop:-10,
      marginBottom:3
    },
    ImageContainer_IP:{
      borderLeftColor:'#F97C2C',
      borderLeftWidth:2,
      marginLeft:20,
      marginTop:-10
    },
    ImageContainer_RD:{
      borderLeftColor:'#5cb85c',
      borderLeftWidth:2,
      marginLeft:20,
      marginTop:-10
    },
    loading: {
      position: 'absolute',
      left: 0,
      right: 0,
      top: 0,
      bottom: 0,
      alignItems: 'center',
      justifyContent: 'center'
    },
    searchSection: {
      flex: 1,
      flexDirection: 'row',
      justifyContent: 'center',
      alignItems: 'center',
      backgroundColor: '#fff',
  },
  searchIcon: {
      padding: 10,
  },
  InputItemReadOnly:{
    borderWidth:0,
    borderRadius:2,
    backgroundColor:'#EBEBE4',
    overflow:'hidden',
    marginBottom:10,
},
ModalContent:{
  backgroundColor: 'white',
  padding: 22,
  justifyContent: 'center',
  alignItems: 'center',
  borderRadius: 4,
  flexDirection:'row',
  borderColor: 'rgba(0, 0, 0, 0.1)' 
},
  input: {
      flex: 1,
      paddingTop: 10,
      paddingRight: 10,
      paddingBottom: 10,
      paddingLeft: 0,
      backgroundColor: '#fff',
      color: '#424242',
  },
  Card:{
    borderRadius:10
  }

  });

