import React, { Component } from 'react';
import {
  StyleSheet,View,TouchableOpacity ,Platform,ScrollView,TextInput,NativeModules,Image,SafeAreaView,AsyncStorage ,ActivityIndicator, TouchableHighlight,InteractionManager
} from 'react-native';
import { Container,
  Header,
  Title,
  Content,
  Body,
  Button,
  Icon,
  ListItem,
  Text,
  Input,
  Left,
  Right,
  Radio,
  List,
  Item,
  Picker,
  Label,Form,Toast} from 'native-base';
  
  import Downshift from 'downshift'; // 1.28.0
import validator  from '../Validation/AddSearchValidator';
import axios from 'react-native-axios';
import Config from 'react-native-config';
import _ from 'lodash';
import SearchableDropdown from 'react-native-searchable-dropdown';
import   Spinner  from 'react-native-loading-spinner-overlay';
import Dropdown from './CustomSearchBar/DropDownALt';
//import { Dropdown } from 'react-native-material-dropdown';
import CustomSelect from './CustomSelect';


import Modal from "react-native-modal";   
const API_URL=Config.API_URL;

//const RNFetchBlob=NativeModules.RNFetchBlob;
// const options={
//    title: 'Upload Image',
//    takePhotoButtonTitle: 'Take a photo with your camera',
//   chooseFromLibraryButtonTitle: 'Choose photo from library',
//   storageOptions: {
//     cameraRoll: true,
//   }
// }


// const inputProps = {
//   keyboardType: 'default',
//   placeholder: 'email',
//   autoFocus: true,
//   style: {
//     fontSize: 14,
//     marginVertical: Platform.OS == 'ios' ? 10 : -2,
//   },
// };

// const horizontalInputProps = {
//   keyboardType: 'default',
//   returnKeyType: 'search',
//   placeholder: 'Enter Tags',
//   style: {
//     fontSize: 14,
//     marginVertical: Platform.OS == 'ios' ? 10 : -2,
//   },
// };



// const horizontalScrollViewProps = {
//   horizontal: true,
//   showsHorizontalScrollIndicator: false,
// };





export const toastr = {
  showToast: (message, duration = 2500) => {
    Toast.show({
      text: message,
      duration,
      position: 'bottom',
      textStyle: { textAlign: 'center' },
  
    });
  },
};








export default class AddVehicle extends Component {

  constructor(props) {
    super(props);
    this.state = {
        Person_id: '',
        loading:true,
        height: 0,
        vehicle_config:[],
        vehicle_category:[],
        FilterData:[],
        business:[],
        people:[],
        specifications:[],
        organization_id:'',
        spec_list_text_array:[],
        IsDisabled:false,
        searchData:'',
        
        loadingSpinner:false,
        ShowButton:false,
        isModalVisible: false,
        addAccountPopup:false,
        States:[],
        Cities:[],
        
        
        person_type:0,
        people_id:'',
        people_data:[],
        spec_list_id:[],
        spec_list_vtext:[],
        spec_list_value:[],
        spec_text_id:[],
        spec_text_value:[],

        vehicle_category_id:'',
        vehicle_config_id:'',
        Registration_no:'',

        name:'',
        nameError:'',
        mobile_no:'',
        mobile_noError:'',
        email:'',
        emailError:'',
        state_id:'',
        state_idError:'',
        city_id:'',
        city_idError:'',
        gst:'',
        gstError:'',
        searchresult:[],
        DisplaySearch:false,
        LoadingSearch:false,
        ConformPopup:false,

        GetExistPeople:[],
        PopupSelect:false,
        PopupState:false,
        PopupCity:false,
        PopupVehicleConfig:false,
        auth_user_id:''
        

    };
   // this.setDate = this.setDate.bind(this);

  }

     /**
     * If setParams using on call back function must implement the 
     * @method navigationOptions 
     * 
     */
    static navigationOptions = ({ navigation }) => {
      const { params } = navigation.state;
        return params;
        
    };

      ResetData(){

        this.setState({
          Person_id: '',
        loading:true,
        height: 0,
        vehicle_config:[],
        vehicle_category:[],
        business:[],
        people:[],
        specifications:[],
        organization_id:'',
        spec_list_text_array:[],
        IsDisabled:false,
        ShowButton:false,
        isModalVisible: false,
        addAccountPopup:false,
        States:[],
        Cities:[],
        
        
        person_type:1,
        people_id:'',
        spec_list_id:[],
        spec_list_vtext:[],
        spec_list_value:[],
        spec_text_id:[],
        spec_text_value:[],

        vehicle_category_id:'',
        vehicle_config_id:'',
        vehicle_config_name:'',

        Registration_no:'',

        name:'',
        nameError:'',
        mobile_no:'',
        mobile_noError:'',
        email:'',
        emailError:'',
        state_id:'',
        stateName:'',
        state_idError:'',
        city_id:'',
        cityName:'',
        city_idError:'',
        gst:'',
        gstError:'',
        searchresult:[],
        DisplaySearch:false,
        
        LoadingSearch:false,
        GetExistPeople:[]
      });

      }

      onItemSelect=(id)=>{

        this.setState({people_id:id,ShowButton:false});
       // console.log(id);

      }


  /**
   * 30-07-19
   * POPUP SCREEN Toggle For Customer,State,City
   *
   * @method toggleSelectPopUp
   */
      toggleSelectPopUp=(name)=>{

      //  console.log(name);

        this.setState({ [name]:!this.state[name]});
      }

            /**
   * 30-07-19
   * POPUP SCREEN Toggle For State
   *
   * @method _HandleSetVehicleConfig 
   */
  _HandleSetVehicleConfig = (item) => {

    console.log(item);
      
    if(item && item.id){

      this.setState({vehicle_config_id:item.id,vehicle_config_name:item.name});
     
    }
  
    } 


       /**
   * 30-07-19
   * POPUP SCREEN Toggle For State
   *
   * @method _HandleSetState 
   */
    _HandleSetState = (item) => {

      console.log(item);
      
      if(item && item.id){

        this.setState({state_id:item.id,stateName:item.name});
        this._getCityById(item.id);
      }
  
    } 


    
       /**
   * 30-07-19
   * POPUP SCREEN Toggle For State
   *
   * @method _HandleSetCity 
   */
  _HandleSetCity = (item) => {

    console.log(item);
    
    if(item && item.id){

      this.setState({city_id:item.id,cityName:item.name});
      //this._getCityById(item.id);
    }

  } 



      _SelectCustomer = (prop) =>{

       // console.log(prop);

        if(prop && prop.id && prop.name && prop.user_type)
        {

          this.setState({people_data:prop,people_id:prop.id,person_type:parseInt(prop.user_type)},this.toggleSelectPopUp('PopupSelect'));
      
        }else{

          alert('Invalid users');

        }
      }

  

      toggleModal = () => {
        
       
        this.setState({ 
          isModalVisible: !this.state.isModalVisible,
          addAccountPopup:false,
          name:'',
          nameError:'',
          mobile_no:'',
          mobile_noError:'',
          email:'',
          emailError:'',
          state_id:'',
          stateName:'',
          state_idError:'',
          city_id:'',
          cityName:'',
          city_idError:'',
          gst:'',
          gstError:'',
          searchError:'',
          DisplaySearch:false,
          auth_user_id:'',
          
          searchresult:[],
          LoadingSearch:false,
          GetExistPeople:[]
        });
      };


  // closeModal() {
  //   this.setState({modalVisible:false});
    
  // //  this._ResetHandler();
  // }

      _SelectUser = (prop) => {
        const { organization_id,business,people,person_type,mobile_no}= this.state;
        //console.log(prop);
       // return;
        let concatData={id:prop.id,name:prop.name};
      
        this.setState({ LoadingSearch:true});
        
        (person_type===1)?this.setState({business:business.concat(concatData)}):this.setState({people:people.concat(concatData)});
        const UserItems=(this.state.person_type===1)?business:people;
        //simple-business-add 
        //console.log(UserItems);
      //  console.log(this.state.business);
      //  return false;

      //   const people_data={
      //     id:prop.id,
      //     mobile_no:mobile_no,
      //     organization_id:organization_id,
          
      //   };

      //   const business_data={
      //     organization_id:organization_id,
      //     person_type:"customer",
      //     id:prop.id
      //   };

      //   const data=(person_type===1)?business_data:people_data;
      //   const url=(person_type===1)?`${API_URL}/simple-business-add`:`${API_URL}/simple-people-add`;
      // //  console.log(url,data);
       // return;

        const url=`${API_URL}/getPeople`;
        const data={
          organization_id:organization_id,
          customer_type:person_type,
          id:prop.id
        };

        console.log(data);

        axios.post(url,data)
        .then(response => {
          // If request is good...
          if(response.status==200){
      

            this.setState({people_id:prop.id,LoadingSearch:false,ConformPopup:!this.state.ConformPopup});
            this._getCityById(response.data.data.state_id);
            this.setState({name:response.data.data.name,
            mobile_no:response.data.data.mobile_no,gst:response.data.data.gst,state_id:response.data.data.state_id,city_id:response.data.data.city_id});
           // this.setState({ isModalVisible: !this.state.isModalVisible,addAccountPopup:false,DisplaySearch:false });
      
          //  this.setState({IsDisabled:true});
          }else{
            
          //  toastr.showToast("Something went to wrong!Please Check the Form Fields");
          }
          
        })
          .catch((error) => {
            console.log('error ' + error.message);
          });


        // console.log(UserItems);
        // this._ResetHandler;
      }

      _SubmitSearch = async() => {

        const ApiToken=await  AsyncStorage.getItem('Token', (err, item) => item);
        
        const {   person_type,
        
          name,
          mobile_no,
          nameError,
          mobile_noError,
          searchError
      } = this.state;
      //alert("Ok");
            //console.log(`${API_URL}/search_user`);
          
          
            if(!name && !mobile_no){

                  this.setState({searchError:"Please fill any field to search! "});
                  return false;
              }else{
                this.setState({searchError:""});

              }
              
              
        this.setState({
          LoadingSearch:true});


        axios.post(`${API_URL}/search_user`,{
        name:name,
        mobile_no:mobile_no,
        person_type:person_type

        
      },{
        headers: {
          Accept: "application/json",
          'Authorization':'Bearer '+ApiToken,
        },
      })
      .then(response => {
        // If request is good...
        // console.log(response.data);
        if(response.status==200){
            this.setState({searchresult:response.data.data});
            let DisplaySearch=(this.state.searchresult.length==0)?true:false;
            
            console.log(DisplaySearch);
            
            this.setState({DisplaySearch:DisplaySearch,LoadingSearch:false});
        //   toastr.showToast("Vechicle Registered Successfully");
        //  this.setState({IsDisabled:true});
        }else{
          
        //  toastr.showToast("Something went to wrong!Please Check the Form Fields");
        }
        
      })
        .catch((error) => {
          console.log('error ' + error.message);
          this.setState({loading:false});
        });
        }

      _CheckGST = (number) =>{
      //  console.log(number);
        axios.post(`${API_URL}/CheckGst`,{
          number:number,
          organization_id:this.state.organization_id

      
        })
        .then(response => {
            console.log(response.data.result);
          // If request is good...
          if(response.status==200){
            
            console.log(response.status);
            this.setState({gstError:(response.data.result)?"GST No already Exist!":null});
          //  this.setState({IsDisabled:true});
          }else{
            
          //  toastr.showToast("Something went to wrong!Please Check the Form Fields");
          }
          
        })
          .catch((error) => {
            console.log('error ' + error.message);
          });
        }


      _CheckMobile = (mobile_no) =>{
      
    
      
      let business_url=`${API_URL}/check/business-mobile`;
      
      let people_url=`${API_URL}/check/people-mobile`;
      
      const url=(this.state.person_type===1)?business_url:people_url;
      
      //console.log(url);


      axios.post(url,{
        mobile_no:mobile_no,
        organization_id:this.state.organization_id
      })
      .then(response => {
          console.log(response);
        // If request is good...
        if(response.status==200){
          
          let  GetExistPeople=(response.data.result)?response.data.data:[];
          this.setState({mobile_noError:(response.data.result)?"Mobile Number already exist.":null});
          this.setState({ConformPopup:(response.data.result)?true:false,GetExistPeople:GetExistPeople});
        //  this.setState({IsDisabled:true});
        }else{
          
        //  toastr.showToast("Something went to wrong!Please Check the Form Fields");
        }
        
      })
        .catch((error) => {
          console.log('error ' + error.message);
        });
        }
    

      _ResetHandler=()=>{
          
          this.setState({
            name:'',
            mobile_no:'',
            searchresult:[],
            searchError:'',
            DisplaySearch:false,
            LoadingSearch:false,
        });
        }

  

      _getCityById=(id)=>{
          
          const state_id=(id)?id:0;
          if(state_id===0)
          {
            return false;
          }
          //console.log(`${API_URL}/getCity/${state_id}`);
          axios.get(`${API_URL}/getCity/${state_id}`)
          .then(response => {
            // If request is good...
            console.log(response.data);
            
            if(response.status==200){
            
              this.setState({Cities:response.data.result});
            
            }else{
              
            //  toastr.showToast("Something went to wrong!Please Check the Form Fields");
            }
            
          })
            .catch((error) => {
              console.log('error ' + error.message);
            });

      }





    _RenderModal = (UserType) =>{
                      let PopupStatus=this.state.addAccountPopup;
                      let GSTField = (
                              <View>

                            
                                  <Item floatingLabel style={[styles.FloatingLabel]}>
                                    <Label>GST (Business must enter the GST)</Label>
                                    <Input
                                      returnKeyType="next"
                                      clearButtonMode="always"
                                      autoCapitalize="none"
                                      autoCorrect={false}

                                      value={this.state.gst}
                                      onChangeText={(text) => {this.setState({ gst: text });this._CheckGST(text)}}
                                      Style={{Color:'#5b5a5a',minHeight:65}}
                                      onBlur={() => {
                                                       if(this.state.person_type==1){

                                                      
                                                        this.setState({
                                                          gstError: (!this.state.gstError)? validator('GST', this.state.gst):this.state.gstError
                                                        })
                                                      }
                                                      }}
                                      />
                                  </Item>
                                  <Text style={[styles.ErrorInput]}> {this.state.gstError ? this.state.gstError :null }</Text>
                                  
                            </View>
                      );
                  // if(PopupStatus)
                  // {
                    
                    return (  
                              
                      <View style={[styles.ModalContent]}>

                      <Content >
                            <View style={{flexDirection:'row'}}>
                                    <Text style={{alignContent:'flex-start',marginTop:'auto',marginBottom:'auto'}}>Add Customer</Text>
                                    <Button title="Hide modal" onPress={this.toggleModal} transparent style={{marginLeft:'auto',marginTop:'auto',marginBottom:'auto',alignItems:'flex-end'}}>
                                            <Icon name='md-close'></Icon>
                                    </Button>
                            </View>
                            
                            <Form>
                            <Label style={[styles.Label_secondary]} >Customer Type</Label>

                            <List>
                            <ListItem
                                  selected={this.toggleRadio(1)}
                                  onPress={() =>{
                                        this.setState({person_type:1 }); 
                                        this.toggleRadio(1)}
                                      }
                                  selectedColor={"#f0ad4e"}
                                >
                                    <Left>
                                    <Text style={[styles.RadioInput]}>Business </Text>
                                  </Left>
                                  <Right>
                                    <Radio
                                      color={"#bfc6ea"}
                                      selectedColor={"#f0ad4e"}
                                      selected={this.toggleRadio(1)}
                                      onPress={() =>{
                                        this.setState({person_type:1 }); 
                                        this.toggleRadio(1);
                                        this.setState({people_id:null});}
                                      }
                                    />
                                  </Right>
                                
                                </ListItem>
                                <ListItem
                                  selected={this.toggleRadio(0)}
                                  onPress={() =>{
                                        this.setState({person_type:0 }); 
                                        this.toggleRadio(0);
                                        this.setState({people_id:null});}
                                      }
                                >
                                  <Left>
                                    <Text style={[styles.RadioInput]}>People</Text>
                                  </Left>
                                  <Right>
                                    <Radio
                                        color={"#bfc6ea"}
                                        selectedColor={"#f0ad4e"}
                                        selected={this.toggleRadio(0)}
                                        onPress={() =>{
                                              this.setState({person_type:0 }); 
                                              this.toggleRadio(0)}
                                          }
                                    />
                                  </Right>
                                </ListItem>
                              
                            </List> 
                            
                              <Item floatingLabel style={[styles.FloatingLabel]}>
                                <Label>Mobile</Label>
                                <Input
                                  returnKeyType="next"
                                  clearButtonMode="always"
                                  autoCapitalize="none"
                                  autoCorrect={false}
                                  value={this.state.mobile_no}
                                  onChangeText={(text) => {this.setState({ mobile_no: text });
                                                          
                                                       if(this.state.GetExistPeople!=[]){
                                                        this._CheckMobile(text);
                                                       }   
                                              }}
                                  
                                  />
                              </Item>
                              <Text style={[styles.ErrorInput]}> {this.state.mobile_noError ? this.state.mobile_noError : null }</Text>
                              <Item floatingLabel style={[styles.FloatingLabel]}>
                                <Label>Customer Name</Label>
                                <Input
                                  returnKeyType="next"
                                  clearButtonMode="always"
                                  autoCapitalize="none"
                                  autoCorrect={false}
                                  value={this.state.name}
                                  onChangeText={(text) => this.setState({ name: text, nameError: validator('Name', text) })}
                                  Style={{Color:'#5b5a5a'}}
                

                                  />
                              </Item>
                              <Text style={[styles.ErrorInput]}> {this.state.nameError ?this.state.nameError : null }</Text>

                              {GSTField}

                              <Item  style={[styles.FloatingLabel,{marginTop:15}]}>

                                <Button transparent onPress={()=>this.toggleSelectPopUp('PopupState')}>
                                      <Text>
                                     {
                                       (this.state.state_id)?this.state.stateName:"Select State"
                                     }
                                      </Text>
                                    </Button>
                                {/* <Label>State</Label>
                                          <Picker
                                                    mode="dropdown"
                                                    Icon={<Icon name="ios-arrow-down" />}
                                                    style={{ width: 100,color:'0d0d0dc7',backgroundColor:'#e8e8e8' }}
                                                    placeholder="Select your SIM"
                                                    placeholderStyle={{ color: "#bfc6ea" }}
                                                    placeholderIconColor="#007aff"
                                            
                                                    itemStyle={{
                                                      backgroundColor: "white",
                                                      
                                                    }}
                                                    itemTextStyle={{ color: "#788ad2" }}
                                                    selectedValue={this.state.state_id}
                                                    onValueChange={(value) => {
                                                        this.setState({
                                                          state_id: value
                                            
                                                            });
                                                            this._getCityById(value);
                                                            console.log(value);
                                                            this.setState({
                                                            state_idError: validator('State', value)
                                                          })
                                                    }}

                                                  
                                                  >
                                                <Item label="SELECT " value="" />
                                                { this.state.States.map((prop, key) => {
                                                              return (
                                                                      <Item label={prop.name} value={prop.id}   itemStyle={{color:'#F97C2C'}}/>
                                                                    
                                                                      );
                                                        })}
                                                
                                                  
                                              
                                        </Picker> */}
                              </Item>
                              <Text style={[styles.ErrorInput]}> {this.state.state_idError ? this.state.state_idError : null }</Text>

                              <Item  style={[styles.FloatingLabel,{marginTop:15}]}>
                                {/* <Label>City</Label> */}
                                <Button transparent onPress={()=>this.toggleSelectPopUp('PopupCity')} disabled={(this.state.state_id)?false:true}>
                                      <Text>
                                     {
                                       (this.state.city_id)?this.state.cityName:"Select City"
                                     }
                                      </Text>
                                    </Button>
                                {/* <Picker
                                                    mode="dropdown"
                                                    Icon={<Icon name="ios-arrow-down" />}
                                                    style={{ width: 100,color:'0d0d0dc7',backgroundColor:'#e8e8e8' }}
                                                    placeholder="Select your SIM"
                                                    placeholderStyle={{ color: "#bfc6ea" }}
                                                    placeholderIconColor="#007aff"
                                            
                                                    itemStyle={{
                                                      backgroundColor: "white",
                                                      
                                                    }}
                                                    itemTextStyle={{ color: "#788ad2" }}
                                                    selectedValue={this.state.city_id}
                                                    onValueChange={(value) => {
                                                        this.setState({
                                                          city_id: value
                                            
                                                            });
                                                            this.setState({
                                                        city_idError: validator('City', value)
                                                          })
                                                            
                                                    }}

                                              
                                          
                                                  >
                                                <Item label="SELECT " value="" />
                                                { this.state.Cities.map((prop, key) => {
                                                              return (
                                                                      <Item label={prop.name} value={prop.id}   itemStyle={{color:'#F97C2C'}}/>
                                                                    
                                                                      );
                                                        })}
                                                
                                                  
                                              
                                        </Picker> */}
                              </Item>
                              <Text style={[styles.ErrorInput]}> {this.state.city_idError ? this.state.city_idError : null }</Text>
                              <List  style={[(this.state.LoadingSearch)?styles.DisplayList:styles.HideList]}>
                                  <ListItem icon noBorder>
                                        <Body>
                                              <ActivityIndicator />
                                      </Body>
                                  </ListItem>
                                </List>
                                <Item style={{borderColor: 'transparent',marginTop:10,marginLeft:'auto'}} >
                                    <Button onPress={this._AddUser}>
                                      <Text>
                                          Add customer
                                      </Text>
                                    </Button>
                                    <Button light style={{marginLeft:8}} onPress={this.toggleModal}>
                                      <Text>
                                        Cancel
                                      </Text>
                                    </Button>
                              </Item>
                            </Form>
                          
                            
                          </Content>
                          
                          
                    </View>

                    );

                  // }else{
                  //   return (
                  //     <View style={[styles.ModalContent]}>

                  //     <Content >
                  //           <View style={{flexDirection:'row'}}>
                  //                   <Text style={{alignContent:'flex-start',marginTop:'auto',marginBottom:'auto'}}>Search {UserType}</Text>
                  //                   <Button title="Hide modal" onPress={this.toggleModal} transparent style={{marginLeft:'auto',marginTop:'auto',marginBottom:'auto',alignItems:'flex-end'}}>
                  //                           <Icon name='md-close'></Icon>
                  //                   </Button>
                  //           </View>
                            
                  //           <Form>
                  //             <Item floatingLabel style={[styles.FloatingLabel]}>
                  //               <Label>{UserType} Name</Label>
                  //               <Input
                  //                 returnKeyType="next"
                  //                 clearButtonMode="always"
                  //                 autoCapitalize="none"
                  //                 autoCorrect={false}
                  //                 value={this.state.name}
                  //                 onChangeText={(text) => 
                  //                                   this.setState({ 
                  //                                     name: text,
                  //                                           })
                  //                                 }
                  //                 onBlur={() => {
                  //                         this.setState({
                  //                           nameError: validator('Name', this.state.name)
                  //                             })
                  //                       }}
                  //                 Style={{Color:'#5b5a5a'}} />
                  //             </Item>
                          
                  //             <Item floatingLabel style={[styles.FloatingLabel]}>
                  //               <Label>Mobile</Label>
                  //               <Input
                  //                 returnKeyType="next"
                  //                 clearButtonMode="always"
                  //                 autoCapitalize="none"
                  //                 autoCorrect={false}
                  //                 value={this.state.mobile_no}
                  //                 onChangeText={(text) => this.setState({ mobile_no: text })}
                  //                 onBlur={() => {
                  //                         this.setState({
                  //                           mobile_noError: validator('Mobile', this.state.mobile_no)
                  //                             })
                  //                       }}
                                        
                  //                       />
                  //             </Item>
                  //             <Text style={[styles.ErrorInput]}> {this.state.searchError  ?this.state.searchError  : null }</Text>


                  //             <Item style={{borderColor: 'transparent',marginTop:10,marginLeft:'auto'}} >
                  //                   <Button onPress={this._SubmitSearch}>
                  //                     <Text>
                  //                         Search
                  //                     </Text>
                  //                   </Button>
                  //                   <Button light style={{marginLeft:8}}  onPress={this._ResetHandler}>
                  //                     <Text>
                  //                       Reset
                  //                     </Text>
                  //                   </Button>
                  //             </Item>
                  //           </Form>
                  //           <View style={{flexDirection:'row'}}>
                  //                   <Text style={{alignContent:'flex-start',marginTop:'auto',marginBottom:'auto'}}>Search Results</Text>
                                
                  //           </View>
                  //         { this.state.searchresult.map((prop,key) => {
                  //           return (
                  //           <List>
                  //                 <ListItem icon noBorder button={true} onPress={()=>this._SelectUser(prop)}>
                  //                       <Left>
                  //                             <Button rounded style={{ backgroundColor: "#FF9501" }}>
                  //                                   <Icon active name="person" />
                  //                             </Button>
                  //                       </Left>
                  //                       <Body>
                  //                         <Text>{prop.name}</Text>
                  //                       </Body>
                  //                 </ListItem>
                  //           </List>
                  //           );
                  //         })} 
                          
                  //           <List  style={[(this.state.LoadingSearch)?styles.DisplayList:styles.HideList]}>
                  //                 <ListItem icon noBorder>
                  //                       <Body>
                  //                             <ActivityIndicator />
                  //                       </Body>
                  //                 </ListItem>
                  //           </List>
                  //           <List  style={[(this.state.DisplaySearch)?styles.DisplayList:styles.HideList]}>
                  //                 <ListItem icon noBorder>
                  //                       <Body>
                  //                         <Text>
                  //                               No results found
                  //                         </Text>
                  //                       </Body>
                  //                 </ListItem>
                  //           </List>
                  //           <List >
                  //                 <ListItem icon noBorder button onPress={()=>{this.setState({addAccountPopup:true})}}>
                  //                       <Left>
                  //                             <Button rounded style={{ backgroundColor: "#cccccc" }}>
                  //                                   <Icon active name="add" />
                  //                             </Button>
                  //                       </Left>
                  //                       <Body>
                  //                         <Text style={{color:'#676767'}}>Add {UserType} </Text>
                  //                       </Body>
                  //                 </ListItem>
                  //           </List>
                  //         </Content>
                          
                          
                  //   </View>




                  //   );

                  // }
    }

    


    loadScreenData =async (personId)  => {
        
        this.ResetData;
        
        const ApiToken=await  AsyncStorage.getItem('Token', (err, item) => item);
        const org_id=await  AsyncStorage.getItem('org_id', (err, item) => item);
        const AuthUser=await  AsyncStorage.getItem('UserData', (err, item) => item);
        const AuthUserId=JSON.parse(AuthUser);
       
        this.setState({auth_user_id:AuthUserId.id});
      
      //  console.log(`${API_URL}/add_vehicle/${personId}/${org_id}`);
        
        axios.get(`${API_URL}/add_vehicle/${personId}/${org_id}`, {
              method: 'GET',
              headers: {
                Accept: "application/json",
                  'Authorization':'Bearer '+ApiToken,
              },
            })
        .then(response => {

          let ResponseData=response.data;
          console.log(ResponseData);
          // _.forEach(ResponseData.customer, function(item) {
          //   
          //   console.log( _.remove(item, {name: null}));
          // });
          _.remove(ResponseData.customer,function(currentObject) {
             
            return currentObject.name == null;
        });


          // console.log("Customer");
          // console.log(ResponseData.customer);
          if(response.status==200)
          {
              this.setState({
                business:ResponseData.business,
                people:ResponseData.people,
                customers:ResponseData.customer,
                vehicle_config:ResponseData.vehicle_config,
                vehicle_category:ResponseData.vehicle_category,
                specifications:ResponseData.spec_data,
                Person_id:personId,
                organization_id:org_id,
                States:ResponseData.state
              });

              ResponseData.spec_data.map((prop,key) => {
                  // console.log(prop);
                  
                /* Spec Data in Loop
                *Check spec list Id

                */
                if(!this.state.spec_list_id.includes(prop.id) && (prop.option && prop.display_name ))
                {
                this.setState({spec_list_id:this.state.spec_list_id.concat(prop.id),spec_list_text_array:this.state.spec_list_text_array.concat(prop.option)});
                }else

                if(!this.state.spec_text_id.includes(prop.id) && ( prop.display_name ))
                {
                this.setState({spec_text_id:this.state.spec_text_id.concat(prop.id),spec_text_value:this.state.spec_text_value.concat(null)});
                }

            //     (!this.state.spec_list_id.includes(prop.id)||!this.state.spec_text_id.includes(prop.spec_text_id))?(prop.option && prop.display_name)?:):null;
                                  
          });

        }
          console.log("Data:"+this.state.specifications);
          console.log(this.state.spec_list_id,this.state.spec_list_text_array,this.state.spec_text_id,this.state.spec_text_value);

    })
    .catch((error) => {
            console.log('error ' + error);
                     
        this.setState({
          loading:false});
      });


    };


    ObjectFilter(array, substr) {

      return _.filter(array, _.flow(
      _.identity,
      _.values,
      _.join,
      _.toLower,
      _.partialRight(_.includes, substr)
    ));

    }

    IsExistValue(text)
    {
      if(text)
      {
      
        const items=(this.state.person_type===1)?this.state.business:this.state.people;
        const data = this.ObjectFilter(items,text);
      
        
        const BtnStatus=(data.length!=0)?false:true;
        console.log(data);
        this.setState({ShowButton:BtnStatus,FilterData:data});
        console.log(data,BtnStatus);
      }else{
        this.setState({ShowButton:false});
      }
    }



    toggleRadio(value){
    
      if(this.state.person_type===value)
      {
      //  this.setState({people_id:null});
        return true;
      }else{
  //      this.setState({people_id:''});
        return false;
      }
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


  _SubmitHandler = async() => {

       
//alert("working");
         const ApiToken=await  AsyncStorage.getItem('Token', (err, item) => item);
         
         const PrevRoute=this.props.navigation.state.routeName;

         const {   person_type,
          people_id,
          spec_list_id,
          spec_list_value,
          spec_list_vtext,
          spec_text_id,
          spec_text_value,
          vehicle_category_id,
          vehicle_config_id,
          Registration_no,
          Person_id,
          organization_id,
          auth_user_id
        } = this.state;

     


    let FormData={
 
      registration_no: Registration_no,
    //  user_type: person_type,
      people_id: people_id,
      //person_id:Person_id,
      //vehicle_name: $('select[name=vehicle_name]').val(),
      vehicle_category: vehicle_category_id,
      vehicle_config:vehicle_config_id,
      organization_id:organization_id,
    //  auth_user_id:auth_user_id
      
    };
  //  console.log(FormData);
    
      for(var propertyName in FormData) {
        // propertyName is what you want
        // you can get the value like this: myObject[propertyName]
      //  if(){
          if(!FormData[propertyName])
          {
            toastr.showToast('Please Fill the all fields',1500);
            return;
          }
      //  }
     }
        this.setState({loadingSpinner:true});
       
        axios.post(`${API_URL}/save_vehicle`,{
          spec_list_id:spec_list_id,
          spec_list_value: spec_list_value,
          spec_list_vtext:spec_list_vtext,
          spec_text_value:spec_text_value,
          spec_text_key:spec_text_id,
          registration_no: Registration_no,
          user_type: person_type,
          people_id: people_id,
          person_id:Person_id,
          auth_user_id:auth_user_id,
          //vehicle_name: $('select[name=vehicle_name]').val(),
          vehicle_category: vehicle_category_id,
          vehicle_config:vehicle_config_id,
          organization_id:organization_id
          
        },{
          headers: {
            Accept: "application/json",
            'Authorization':'Bearer '+ApiToken,
          },
        })
        .then(response => {

         console.log(response);
         this.setState({loadingSpinner:false});
          // If request is good...
          if(response.status==200){

            
            // console.log(response);

                  
            // console.log("efd");
            // console.log(response.data.status);
            if(response.data.status==0)
            {
              alert(response.data.ErrorMessage);
              return;
            }
            
            let result=toastr.showToast("Vechicle Registered Successfully");
            
         //   return false;
            // alert(PrevRoute);
            // alert((PrevRoute=="Popup")?"Popup":"No Popup");
          
           if(PrevRoute=="Popup")
            {
             // alert(PrevRoute);
                // this.props.navigation.navigate('JobCardCreate',{Vehicle_id:response.data.id,Vehicle_name:response.data.name});
               //  alert(response.data.id);
               this.props.navigation.state.params.returnData(response.data.id, response.data.name);
               this.props.navigation.goBack();
               return;

            }

           
              this.props.navigation.push('User');

           

          //  this.setState({IsDisabled:true});
          }else{
            
            toastr.showToast("Something went to wrong!Please Check the Form Fields");
          }
          
        })
          .catch((error) => {
        //  console.log('error ' + error.message);
        //  console.log('error ' + error);
          });
          

          }

  onUpdateSpec = (i,value) => {
      const SpecArray=this.state.spec_list_value;
      const SpecListTextData=this.state.spec_list_text_array;
      console.log(SpecListTextData)
      SpecArray[i]=value;
      const SpecTextArray=this.state.spec_list_vtext;
      



        let getSpecListData = _(SpecListTextData)
        .thru(function(coll) {
          return _.union(coll, _.map(coll, 'children'));
        })
        .flatten()
        .find({ id: value });;
      // console.log(value,SpecListTextData,getSpecListData);
      if(getSpecListData)
      {
        SpecTextArray[i]=getSpecListData.value;

      }
      
        this.setState({spec_list_value:SpecArray,spec_list_vtext:SpecTextArray});
        console.log(SpecArray,SpecTextArray);
  

  };

  onUpdateSpecText = (i,value) => {
    const SpecTextArray=this.state.spec_text_value;
    SpecTextArray[i]=value;
    this.setState({spec_text_value:SpecTextArray})

  }

  getSpecValue = (i) => {
    const SpecArray=this.state.spec_list_value;
  // console.log(SpecArray);
    return SpecArray[i];
  }

  getSpecText = (i) => {
    const SpecTextArray=this.state.spec_text_value;
      console.log(SpecTextArray);
    return SpecTextArray[i];
  }


  _AddUser = async() => {

  const ApiToken=await  AsyncStorage.getItem('Token', (err, item) => item);
  
  const {   person_type,
    name,
    nameError,
    mobile_no,
    mobile_noError,
    email,
    city_id,
    city_idError,
    state_idError,
    gst,
    gstError,
    organization_id,
    GetExistPeople,
    auth_user_id
  } = this.state;


      let NameSError= validator('Name', this.state.name);
    //  alert((this.state.GetExistPeople==[])?"empty":"not empty");
    let Mobile_noSError=(mobile_no && this.state.GetExistPeople==[])?this._CheckMobile(mobile_no):validator('Mobile', this.state.mobile_no);
    let State_idSError = validator('State', this.state.state_id);
    let City_idSError= validator('City', this.state.city_id);
    let GST_Error= validator('GST', this.state.gst);
    this.setState({nameError:NameSError,mobile_noError:Mobile_noSError,state_idError:State_idSError,city_idError:City_idSError,gstError:(person_type==0)?null: GST_Error});                             
    
    //  alert(GST_Error);
    
    if(person_type)
    {
      if(person_type==1 && (NameSError || Mobile_noSError || City_idSError ||State_idSError||GST_Error))
      {
        // alert("working");
        return false;
      }
      if(person_type==0 && (NameSError || Mobile_noSError || City_idSError ||State_idSError))
      {
        // alert("working");
        return false;
      }
    }
    
    
    
    
    const BusinessData={
    business_name:name,
    business_mobile:mobile_no,
    business_email:email,
    business_city:city_id,
    organization_id:organization_id,
    gst:gst,
    person_type:"customer",
    IsExistData:(GetExistPeople && Object.keys(GetExistPeople).length  >0)?1:0,
    id:(GetExistPeople && Object.keys(GetExistPeople).length >0)?GetExistPeople.id:null,
    auth_user_id:auth_user_id
  };

  const PeopleData={
    first_name:name,
    mobile_no:mobile_no,
    email:email,
    city_id:city_id,
    organization_id:organization_id,
    person_type:"customer",
    IsExistData:(GetExistPeople && Object.keys(GetExistPeople).length  >0)?1:0,
    id:(GetExistPeople && Object.keys(GetExistPeople).length >0)?GetExistPeople.id:null,
    auth_user_id:auth_user_id
  };
  const data=(person_type===1)?BusinessData:PeopleData;

  const url=(person_type===1)?`${API_URL}/add_business`:`${API_URL}/add_user`;
  
  

      console.log("URL:"+url);
      console.log(data);
     // return;
  axios.post(url,data,{
    headers: {
      Accept: "application/json",
      'Authorization':'Bearer '+ApiToken,
    },
  })
  .then(response => {
    // If request is good...
    
   // console.log(response.data);
    const AppendData=response.data.data;
    // console.log(AppendData);
    // console.log(response.status);
    // console.log(response.data.data);
    // console.log(response.data.original);

    (person_type===1)?this.setState({business:this.state.business.concat(AppendData)}):this.setState({people:this.state.people.concat(AppendData)});
 

   
  //  this.closeModal();
  //  this._ResetHandler();
   // console.log(response.status);
    if(response.status==200){

      toastr.showToast("User Added Successfully");
      this.setState({ isModalVisible: !this.state.isModalVisible,people_data:AppendData });
      this.setState({people_id:AppendData.id,name:'',
      mobile_no:'',gst:'',state_id:'',city_id:''});
    
    // this.setState({IsDisabled:true});

    }else{
      
      toastr.showToast("Something went to wrong!Please Check the Form Fields");
    }
    
  })
    .catch((error) => {
      console.log('error ' + error.message);
    });
    

    }

  _trimString= (str)=>{

    
    // const UString=str.toUpperCase();
    
    const Registration_no=str.trim();
    this.setState({Registration_no:Registration_no});

    }


  componentDidMount()
  {
    const {routeName } =  this.props.navigation.state;
   // console.log(routeName);
      const Token = AsyncStorage.getItem('Token');
      
      if(Token){

        AsyncStorage.getItem('PersonId').then(PersonId => {
       
        this.setState({Person_id:PersonId});
        let FetchData=this.loadScreenData(PersonId);

        this.props.navigation.setParams({ handleSave: this.loadScreenData(PersonId) });
          this.setState({loading:true});
      
      });
      

      }else{
      
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
                Information Exist!Do you want to add in Organization?
                 </Text>
               
    
                 <List  style={[(this.state.LoadingSearch)?styles.DisplayList:styles.HideList]}>
                                 <ListItem icon noBorder>
                                       <Body>
                                            <ActivityIndicator />
                                       </Body>
                                 </ListItem>
                  </List>

           <Item style={{borderColor: 'transparent',marginTop:10,marginLeft:'auto'}} >
           
                <Button onPress={()=>{this._SelectUser(this.state.GetExistPeople)}} disabled={this.state.LoadingSearch}>
                  <Text>
                      Conform
                  </Text>
                </Button>
    
                <Button light style={{marginLeft:8}}  onPress={()=>{this.setState({ConformPopup:!this.state.ConformPopup},this._ResetHandler)}} disabled={this.state.LoadingSearch}>
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

  



    render() {
         //   console.log(response);
            const {  loading,customers,person_type,people,business,vehicle_config,vehicle_category,specifications,IsDisabled,States,Cities,loadingSpinner} = this.state;
            let PeopleList=(person_type===1)?business:people;  
            let OtherPeople=(person_type===1)?people:business;
            let SelectName=(person_type===1)?"Select Business":"Select People";
            let user=(person_type===1)?"Search Business":"Search People";
            let UserType = (person_type===1)?"Business":"People";
            let PopupContent=this._RenderModal;
     



      if(loading) { 

        return (
            <Container style={[styles.Container]}>
                   
                  
                   <Spinner visible={loadingSpinner}  textContent={'Save processing...'} />
        
                  {/* Modal Popup */}
                  
                  <Modal isVisible={this.state.isModalVisible} onRequestClose={() => this.toggleModal} >
                        {this._RenderModal()}
                      
                  </Modal>

                  <CustomSelect  ModalVisible={this.state.PopupVehicleConfig} Data={vehicle_config} PopupEvent={()=>this.toggleSelectPopUp('PopupVehicleConfig')}  SelectEvent={this._HandleSetVehicleConfig} PopupTitle={"Vehicle Configuration"} DisableButton={true} icon={"car"}/>
                 
                  <CustomSelect  ModalVisible={this.state.PopupState} Data={States} PopupEvent={()=>this.toggleSelectPopUp('PopupState')}  SelectEvent={this._HandleSetState} PopupTitle={"State"} DisableButton={true} />
                
                  <CustomSelect  ModalVisible={this.state.PopupCity} Data={Cities} PopupEvent={()=>this.toggleSelectPopUp('PopupCity')}  SelectEvent={this._HandleSetCity} PopupTitle={"City"} DisableButton={true}/>

                  {this.ConfirmationPopup()} 

                  <CustomSelect ModalVisible={this.state.PopupSelect}  Data={customers} PopupEvent={()=>this.toggleSelectPopUp('PopupSelect')}  CustomerPopup={this.toggleModal} SelectEvent={this._SelectCustomer}  PopupTitle={"Add Customer"} icon={"person"}/> 
                 
                  {/* Modal Popup */}

                  <ScrollView>
                  
                  <Content padder >
                        
                      <Label style={[styles.Title,{fontweight:'bold'}]} >Add Register Vehicle Beta</Label>
                          <View
                            style={{
                              borderWidth: 1,
                              borderColor: '#e8e8e8',
                              borderRadius:3,
                              marginBottom:12,
                              }}
                            />
                    <Form style={{marginBottom:50}}>
                    <Label style={[styles.Label]} >Registration Number</Label>
            
                      <Item regular style={[styles.InputItem]}>
                          <Input  value={this.state.Registration_no}
                              placeholder="TN01G999"
                              style={[styles.Input,{height: Math.max(40, this.state.height)}]}  
                              onChangeText={(value) => this.setState({IsDisabled:false},this._trimString(value))}
                          />
                          <Text style={[styles.ErrorInput]} placeholder="TN01AA1234"> {this.state.TaskNameError ? this.state.TaskNameError : null }</Text>

                      </Item>

                      
                         {/* <Label style={[styles.Label_secondary]} >Customer Type</Label>

                        <List>
                        <ListItem
                              selected={this.toggleRadio(1)}
                              onPress={() =>{
                                    this.setState({person_type:1 }); 
                                    this.toggleRadio(1)}
                                  }
                              selectedColor={"#f0ad4e"}
                            >
                                <Left>
                                <Text style={[styles.RadioInput]}>Business </Text>
                              </Left>
                              <Right>
                                <Radio
                                  color={"#bfc6ea"}
                                  selectedColor={"#f0ad4e"}
                                  selected={this.toggleRadio(1)}
                                  onPress={() =>{
                                    this.setState({person_type:1 }); 
                                    this.toggleRadio(1);
                                    this.setState({people_id:null});}
                                  }
                                />
                              </Right>
                            
                            </ListItem>
                            <ListItem
                              selected={this.toggleRadio(0)}
                              onPress={() =>{
                                    this.setState({person_type:0 }); 
                                    this.toggleRadio(0);
                                    this.setState({people_id:null});}
                                  }
                            >
                              <Left>
                                <Text style={[styles.RadioInput]}>People</Text>
                              </Left>
                              <Right>
                                <Radio
                                    color={"#bfc6ea"}
                                    selectedColor={"#f0ad4e"}
                                    selected={this.toggleRadio(0)}
                                    onPress={() =>{
                                          this.setState({person_type:0 }); 
                                          this.toggleRadio(0)}
                                      }
                                />
                              </Right>
                            </ListItem>
                          
                      </List>   */}
                      <Label style={[styles.Label_secondary]} >Customer</Label>
                      <View>
                          {/* <Dropdown Data={PeopleList}   onItemSelect={this.onItemSelect} />    
                           */}
                             </View>
                      <Item   regular >
                      
                          {/* <Input  onFocus={this.toggleSelectPopUp}
                              placeholder="Select Customer"
                              style={[styles.Input,{height: Math.max(40, this.state.height)}]}  
                                  /> */}
                                  <Button transparent onPress={()=>this.toggleSelectPopUp('PopupSelect')}>
                                      <Text>
                                     {
                                       (this.state.people_data && Object.keys(this.state.people_data).length  >0)?this.state.people_data.name:"Select Customer"
                                     }
                                      </Text>
                                  </Button>

                       
                      {/* <Dropdown label='Select Job status' data={SelectData} containerStyle={{width:'100%',borderBottomWidth:0}} labelFontSize={14} 
                              onChangeText={(value)=>{this.IsExistValue(value)}}
                              value={1}
                              
                             /> */}
                             {/* <SearchableDropdown
                                        onTextChange={text =>{this.IsExistValue(text)}}
                                       
                                    
                                        containerStyle={{ padding: 5 }}
                                        textInputStyle={{
                                          padding: 12,
                                          borderWidth: 1,
                                          borderColor: 'white',
                                        
                                        }}
                                        itemStyle={{
                                          padding: 5,
                                          marginTop: 2,
                                          backgroundColor: '#ddd',
                                         
                                        }}
                                        itemTextStyle={{ color: '#222' }}
                                        itemsContainerStyle={{ maxHeight: 140,width:200 }}
                                        items={PeopleList}
                                        defaultIndex={this.state.people_id}
                                        placeholder="select User"
                                        resetValue={false}
                                        underlineColorAndroid="transparent"

                                      /> */}
                                      <Button  disabled={!this.state.ShowButton} style={[(this.state.ShowButton)?{opacity:1,marginLeft:'auto',marginTop:-1,paddingTop:35,paddingBottom:35,height:50}:{opacity:0}]}  onPress={this.toggleModal}>
                                          <Text>
                                                Add customer
                                          </Text>
                                      </Button> 
                                                              
                                      
                      </Item>

                    
                    <Label style={[styles.Label_secondary]} >Vehicle Configuration</Label>
                    
                    <Item regular style={[styles.InputItem]}>

                                  <Button transparent onPress={()=>this.toggleSelectPopUp('PopupVehicleConfig')}>
                                      <Text>
                                     {
                                       (this.state.vehicle_config_id)?this.state.vehicle_config_name:"Select Vehicle Configuration"
                                     }
                                      </Text>
                                  </Button>
                        {/* <Picker
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
                              selectedValue={this.state.vehicle_config_id}
                              onValueChange={(value) => {
                                  this.setState({
                                    vehicle_config_id: value
                      
                                      });
                          
                               }}
                    
                            >
                          <Item label="SELECT " value="" />
                          { vehicle_config.map((prop, key) => {
                                        return (
                                                <Item label={prop.vehicle_configuration} value={prop.id}   itemStyle={{color:'#F97C2C'}}/>
                                              
                                                );
                                  })}
                          
                            
                        
                          </Picker> */}
                          {/* <Text style={[styles.ErrorInput]}> {this.state.AssignedByError ? this.state.AssignedByError : null }</Text> */}

                  </Item>
                  <Label style={[styles.Label_secondary]} >Vehicle Category</Label>
                    
                    <Item regular style={[styles.InputItem]}>
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
                        selectedValue={this.state.vehicle_category_id}
                        onValueChange={(value) => {
                          this.setState({
                            vehicle_category_id: value
                              });
                          
                          }}
                      
                    >
                      <Item label="SELECT" value="" />
                      { vehicle_category.map((prop, key) => {
                                        return (
                                                <Item label={prop.name} value={prop.id}   itemStyle={{color:'#F97C2C'}}/>
                                              
                                                );
                                  })}
                           
                    </Picker>
                    {/* <Text style={[styles.ErrorInput]}> {this.state.AssignedToError ? this.state.AssignedToError : null }</Text> */}
                        
                  </Item>
                  
              
                 
                  { specifications.map((prop,key) => {
                    {/* const listItems = prop.option.map((name,id) =>
                                 <Item label={name} value={id} />
                                        ); */
                                      
                                        }
                                    
                                             return (
                                               (prop.display_name)?
                                                         ( <View> 
                                                                <Label style={[styles.Label_secondary]} >{prop.display_name}</Label>   
                                                                

                                                            {(prop.option)?(
                                                            
                                                              <Item regular style={[styles.InputItem]}>
                                                                    <Picker
                                                                      mode="dropdown"
                                                                      Icon={<Icon name="ios-arrow-down" />}
                                                                      style={{ width: 100,color:'0d0d0dc7',backgroundColor:'white',Height:10 }}
                                                                      placeholder="Select your SIM"
                                                                      placeholderStyle={{ color: "#bfc6ea" }}
                                                                      placeholderIconColor="#007aff"
                                                                    
                                                                      itemStyle={{
                                                                      backgroundColor: "white",
                                                                      
                                                                      }}
                                                                      itemTextStyle={{ color: "#788ad2" }}
                                                                      selectedValue={this.getSpecValue(prop.key)}
                                                                      
                                                                      onValueChange={(value) => this.onUpdateSpec(prop.key,value)}
                                          
                                    
                                                                  
                                                                  >
                                                                  <Item label="SELECT" value="" />
                                                                  {prop.option.map((name) => {
                                                                  
                                                                        return (
                                                                          <Item label={name.value} value={name.id} />
                                                                        );
                                                                      })}
                                                                </Picker>
                                                            </Item>
                                                            ):(
                                                              <Item regular style={[styles.InputItem]}> 
                                                                        <Input value={this.getSpecText(prop.key)}
                                                                            placeholder=""
                                                                            style={[styles.Input,{height: Math.max(35, this.state.height)}]}  
                                                                            onChangeText={(value) => this.onUpdateSpecText(prop.key,value)}
                                                                    />
                                                              </Item>      

                                                            )}
                                                      
                                                          
                                                      </View>):(null)
                  );
                                       
                                          

                                  })}


                               
                      </Form>
                    
                  </Content>
                 
                  </ScrollView>
                
                  <Button rounded iconLeft style={{       
                        backgroundColor: '#F97C2C',                                    
                        position: 'absolute',
                        alignSelf:'center',                                          
                        bottom: 10,
                        zIndex:10                                                    
                            }}  

                        onPress={this._SubmitHandler}
                        disabled={IsDisabled}>
                        <Icon name="md-add" style={{color:'#fff'}}/>
                        <Text>Add Vehicle</Text>
                </Button>
            </Container>
         
        );
      }else{
        return <ActivityIndicator />
      }
    }
}


const styles = StyleSheet.create({
    noBorder: {
     borderBottomWidth:0
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
      marginBottom:3,
      fontSize:18
      
    },
    Label_secondary:{
      color:'#0d0d0dc7',
      paddingTop:10,
      fontSize:14
    },
    InputItem:{
        borderWidth:0,
        borderRadius:10,
        backgroundColor:'white',
       overflow:'hidden'
  
    },
    DisplayImage:{
      width:'50%',
      minHeight:100,
      margin:10,
      alignSelf:'center'
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
      fontSize:13,
      paddingLeft:11
    },
    Input:{
       fontSize:14
    },
    RadioInput:{
      fontSize:14
    },
    Container:{
      backgroundColor:'#f6f6f6',
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
    FloatingLabel:{
      backgroundColor: '#e8e8e8',
       borderColor: 'transparent',
       borderBottomColor:'#6c6c6c',
       borderTopLeftRadius:2,
       borderTopRightRadius:2
  },
  textInput: {
    alignItems: 'flex-start',
    backgroundColor: '#F0F0F0',
    padding: 12,
    borderRadius: 8,
    marginBottom: 2,
    height:50,
    width:50
  },
  list: {
    borderRadius: 8,
    overflow: 'hidden'
  },
  listItem: {
    alignItems: 'flex-start',
    backgroundColor: '#E0E0E0',
    padding: 12
  },
  intro: {
    backgroundColor: '#444',
    padding: 12,
    borderRadius: 2,
    marginBottom: 12
  },
  introText: {
    color: '#fff'
  },
  DisplayList:{
    display:'flex'
  },
  HideList:{
    display:"none"
  }
 
  });
module.export = AddVehicle;