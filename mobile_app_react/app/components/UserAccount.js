import React, { Component } from "react";
import { StyleSheet,View,Alert  ,Platform,ScrollView,Image,TouchableOpacity,Dimensions,PixelRatio,AsyncStorage,BackHandler } from "react-native";

import { Container,Icon,Badge, Content, Accordion,Button,Item,  Picker,
         DatePicker,Toast ,Text,Textarea,Input,Spinner,InputGroup , Form,Label,Grid,Col,ListItem,Left,Body,CheckBox  } from "native-base";
import LinearGradient from 'react-native-linear-gradient';

import axios from 'react-native-axios';
import _ from 'lodash';
import Config from 'react-native-config';
import { NavigationActions } from 'react-navigation';





var {width} = Dimensions.get('window');
const API_URL=Config.API_URL;
const ScreensHeading = [
  { title: "Job Header"},
  { title: "Photos" },
  { title: "CheckList" }
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
  }
}

 



export default class UserAccount extends Component {

  constructor(props) {
    super(props);
    this.state = {

      loading:false,
      organization_id:'',
      organizations_data:[],
      Person_id:'',
      showToast: false
      //FORM DATA
  
    }

   
  }
  
  _GetLoadData =async(personId)=>{

    const ApiToken=await  AsyncStorage.getItem('Token', (err, item) => item);
    console.log(ApiToken);
    console.log(`${API_URL}/user_companies/${personId}`);
    axios.get(`${API_URL}/user_companies/${personId}`, {
      method: 'GET',
      headers: {
       Accept: "application/json",
          'Authorization':'Bearer '+ApiToken,
      },
    })
.then(response => {
         // If request is good...
  let ResponseData=response.data;
  // if(response.error)
  // {

  //   console.log('invalid Token');
  //   AsyncStorage.removeItem('Token');
  // }
      console.log(ResponseData);
    //  return;
  if(ResponseData.status==1 && response.status==200)
     {
       
      const OrganizationData= ResponseData.data.Org_data;

     

       //return false;
       this.setState({
        organizations_data:ResponseData.data.Org_data,
       
         loading:false
         });
      
   //      console.log(ResponseData.data.Org_data);

     }else{
       //  console.log(response.status);
      // AsyncStorage.removeItem('Token');
     }

   })
.catch((error) => {
      console.log(error);
      Alert.alert(
        'Exit App',
        'Do you want to exit?',
        [
          {text: 'No', onPress: () => console.log('Cancel Pressed'), style: 'cancel'},
          {text: 'Yes', onPress: () => BackHandler.exitApp()},
        ],
        { cancelable: false });
      
       
 });
  }


  componentDidMount()
  {
    console.log("User Account?");

    console.log(this.props);
      const Token = AsyncStorage.getItem('Token');
      if(Token){

          AsyncStorage.getItem('PersonId').then(PersonId => {
          
          //  let UserData= JSON.parse(asyncStorageRes);
            console.log(PersonId);
          this.setState({Person_id:PersonId});
             
          this._GetLoadData(PersonId);

     
        });

  
    }
  }


  _SubmitHandler = async(organization_id) =>
  {
     
      if(organization_id)
      {
     
     
          this.props.navigation.navigate("Dashboard", {org_id:organization_id});

      
     }else{
       console.log("not working");
     }
  }

    
    
    
    

  render() {
    const { loading,organizations_data }= this.state;
    if(!loading) { 
      
                    
      return (
      <Container style={{backgroundColor:'#ff8008',flexDirection:'row',alignItems:'center'}}>
        <LinearGradient
          colors={['#ff7e5f', 'transparent']}
          style={{
            position: 'absolute',
            left: 0,
            right: 0,
            top: 0,
            height: 800,
          }}
        />
          <Content padder>

          <Grid style={{alignItems: 'center',flexDirection:'column'}}>
                    <Col>
                                {/* <View rounded large  style={{margin:'auto', height:128,
            width: 128,
            backgroundColor:'#b35113'}}> */}
                        <Body>

                      
                            <Image
                        style={styles.image }
                        source={require('../../assets/images/logo_trans.png')}
                        resizeMode="contain" 
                        />
                          </Body>
                                {/* </View> */}
                                <Text style={{color:'white',fontWeight:'bold',alignSelf:'center'}}>

                                  Choose the company
                                </Text>

                    </Col>
                    <Col style={{marginLeft:'auto',marginRight:'auto'}}>
                      {
                        (organizations_data.length==0) 
                        ?<Spinner/>
                        :organizations_data.map((prop, key) => {
                                        return (
                                          <Button rounded  transparent  
                                                style={{
                                                  backgroundColor:"#b35113",
                                                  marginLeft:'auto',
                                                  marginRight:'auto',
                                                  marginBottom:5,
                                                  marginTop:5}}
                                                 onPress={() => this._SubmitHandler(prop.id)}
                                                  >
                                                <Text style={{color:'white'}}>{prop.name}</Text>
                                           </Button>
                                                );
                                  })
                                  }
                        
                         
                         
                      </Col>
                    </Grid>
          
          </Content>

          


      </Container>

    );
    }else{
        return (
                  <Content contentContainerStyle={{flex: 1}} style={{padding: 10}}>
                    <Grid>
                      <Col>
                          <Spinner/>
                      </Col>
                    </Grid>
                  </Content>
        );
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
        backgroundColor:'#f6f6f6',
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
      image:{
        width: width * 0.5,
       marginTop:width * 0.10
     },

    });
