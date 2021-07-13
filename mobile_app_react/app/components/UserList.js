import React, { Component } from 'react';
import {
  StyleSheet,View ,Platform,Dimensions,PixelRatio,AsyncStorage,FlatList,ActivityIndicator ,TouchableWithoutFeedback,TouchableOpacity
} from 'react-native';
import { Container,
  Header,
  Title,
  Content,
  Button,
  Icon,
  ListItem,
  Text,
  Badge,
  Left,
  Right,
  Body,
  Switch,
  Radio,
  Item,
  Picker,Card,CardItem,Form,Input,
  Separator,Label, Fab, List,Thumbnail,H6} from 'native-base';
import AppHeader from'./appHeader';
import AppFooter from'./appFooter';
import Modal from "react-native-modal";
import ActionButton from 'react-native-action-button';
import CustomEmptyList from './CustomEmptyList';
//import SearchPopup from './SearchPopup';
import axios from 'react-native-axios';
import Config from 'react-native-config';
import { NavigationActions } from 'react-navigation';
import call from 'react-native-phone-call';
import SearchPopup from './SearchPopup';
import ConfirmBox from './CustomConfirmation';
  
import { Dropdown } from 'react-native-material-dropdown';



const API_URL=Config.API_URL;
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


export function _findItem(query,JobcardList) {
  if (query === '') {
    return [];
  }
  
 
 

  if(query && isNaN(query) )
  {
   // console.log(query);
    const regex = new RegExp(`${query.trim()}`, 'i');
 
    return JobcardList.filter(item => item.name.search(regex) >= 0);
  
  }else{
    return [];
  }
}

export default class UserList extends Component {
    /**
     * If setParams using on call back function must implement the 
     * @method navigationOptions 
     * 
     */
          static navigationOptions = ({ navigation }) => {
            const { params } = navigation.state;
              return params;
          };
  
    constructor(props) {
    super(props);
    this.onEndReachedCalledDuringMomentum = true;


    this.state = {
      InvoiceList:[],
   
      token:null,
      PersonId:null,
      org_id:null,
      page:0,
      loading:true,
      loadingContent:false,
      //Search Terms
      jobcard_no:'',
      customer_name:'',
      
      refreshing: false,

      mobile_no:'',
      //Confirmation Popup
      ConfirmationPopup:false
    };
  }






      

    _ResetSearch=()=>{
      
      //  const SearchStates=this.state.SearchStates;
        
        this.setState({jobcard_no:'', customer_name:'',Search_status:'',JobcardStatus:''});
  
      //  console.log(SearchStates);
  
        }
  
        
          /**
          * @method _HandleSearchInput
          * 
          * 
          * 
          *Update state with value in Search Popup 
          */
  
        _HandleSearchInput=(state_type,value)=>{
          
          this.setState({[state_type]:value});
         // alert(state_type,value);
             
             //console.log(this.state[state_type]);
      
            }
  
  
  
  /**
        * @method LoadDataHandler
        * 
        * Retrieve the Data from API
        * 
        * We will call this function on ComponentDidMount or ComponentWillMount 
        */


      LoadDataHandler = async ()=> {

        const { jobcard_no,customer_name }= this.state;
       
        const ApiToken=await  AsyncStorage.getItem('Token', (err, item) => item);
        const Organization_Id=await  AsyncStorage.getItem('org_id', (err, item) => item);
        const PersonId=await  AsyncStorage.getItem('PersonId', (err, item) => item);
      
            let pageSize=5;
            let page=this.state.page;
      


            let URL_PRO=`${API_URL}/job_invoice_list`;

            // console.log({
            //   person_id:PersonId,
            //   org_id:Organization_Id,
            //   page:page,
            //   per_page:pageSize,
            //   jobcard_no:jobcard_no,
            //   customer_name:customer_name
              
            //  });
            // console.log(URL_PRO);
         
         axios.post(URL_PRO,{
              person_id:PersonId,
              org_id:Organization_Id,
              page:page,
              per_page:pageSize,
              jobcard_no:jobcard_no,
              customer_name:customer_name,
              
             }, {
              headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'Authorization':'Bearer '+ApiToken,
              }
              
            }).then(response =>{
              
              console.log(response);
              //console.log(response);
              //console.log("working");
             // return false;
         //    return false;
              if(response.status==200){
                
                this.setState({
  
                 // InvoiceList:this.state.InvoiceList.concat(response.data.jobInvoice),
                  InvoiceList: (page==0)?response.data.jobInvoice:this.state.InvoiceList.concat(response.data.jobInvoice),
            
                  loading:false,
                  loadingContent:false,
                
                  token:ApiToken,
                  PersonId:PersonId,
                  org_id:Organization_Id,
                  refreshing: false
    
                    });
                 //   console.log(response.data);
                  }
    
             })
              .catch((error) => {
                console.error(error);
              });
          }


          
      

          
       /**
        * @method Search
       *  Search Method fetch data from Api.Search Option depends on screen. 
       *  In UserList Screen,there are two fields like customer name, job card no.
       *  Search 
       *      in jobcard - 1 ,invoice - 2, both -3
       * 
       */


      _Search = () =>{


        const { jobcard_no,IsSearch,customer_name,token,PersonId,org_id,page }= this.state;

        let pageSize=5;


       // const searchStatus= (this.props.navigation.getParam("CurrentPage",false)==1)?3:2;
       
       this.props.navigation.setParams({
          SearchResult:true,
        });
        //this.props.navigation.dispatch(navigationAction);
        
        
        this.setState({ loading:true,loadingContent:false,page:0})
        
        
        
        let URL_PRO=`${API_URL}/job_invoice_list`;
       
    
       axios.post(URL_PRO,{
            person_id:PersonId,
            org_id:org_id,
            page:page,
            per_page:pageSize,
            jobcard_no:jobcard_no,
            customer_name:customer_name
            
           }, {
            headers: {
              Accept: 'application/json',
              'Content-Type': 'application/json',
              'Authorization':'Bearer '+token,
            }
            
          }).then(response =>{
            
            
            console.log(response.data.jobInvoice);
            
        //    return false;
            
            
            if(response.status==200){
              
              this.setState({

                      InvoiceList:response.data.jobInvoice,
                      loading:false,
                      loadingContent:false,
                      IsSearch:true
                 
  
                  });
                //  console.log(response.data.jobInvoice);
                }
  
           })
            .catch((error) => {
              console.error(error);
            });
  
        //  console.log(this.state.JobcardList);

      }
        /**
       * 
       *
       * @method componentWillMount
       */

      
       componentWillMount() {
          
        const { navigation } = this.props;

        navigation.setParams({ isModalVisible: false,ResetSearch: this._ResetSearch,HandleSearchInput:this._HandleSearchInput,HandleSearch:this._Search,IsSearchResult:this.state.IsSearch,SearchResult:navigation.getParam('SearchResult',false),CurrentPage:navigation.getParam('CurrentPage',false) });

        this.setState({loadingContent:true,page:0,JobcardList:[],jobcard_no:'', customer_name:'',Search_status:''},this.LoadDataHandler,this.SetNavigationParams);
        //navigation.getParam('SearchResult',false)
        
        
        
        this.subs = [navigation.addListener('didFocus',() =>{
          
         if(this.state.IsSearch)
            {

              this.setState({ loading:true,loadingContent:false,page:0,InvoiceList:[],jobcard_no:'', customer_name:'',IsSearch:false},this.LoadDataHandler,this.SetNavigationParams);
           
            }else{

              this.setState({jobcard_no:'', customer_name:'',Search_status:'',IsSearch:false},this.SetNavigationParams);
        

            }
        
      
        })];
    }


    /* Start Confirmation */
    
    
    /* End Confirmation */


    SetNavigationParams=()=>{
         
      const { navigation } = this.props;

        const navigationActions=navigation.setParams({ isModalVisible: false,ResetSearch: this._ResetSearch,HandleSearchInput:this._HandleSearchInput,HandleSearch:this._Search,IsSearchResult:this.state.IsSearch,SearchResult:false });
        navigation.dispatch(navigationActions);
        //console.log("Working");
     }

        /**
         * Componentwillmount unset  the array of this.subs - property
         * it will be trigger after chaning the tab menu.
         * Purpose of re-rendering data from API in every click of this Tab
         * 
         * @method componentWillUnmount
         */



      componentWillUnmount() {


        const { navigation } = this.props;
        navigation.setParams({ isModalVisible: false,ResetSearch: '',HandleSearchInput:'',HandleSearch:'',IsSearchResult:''});

        
        // this.subs.forEach((sub) => {
        //   sub.remove();
        // });
    }




    _handleRefresh = () => {

      this.setState(
        {
          page: 0,
          refreshing: true
        },
        
          this.LoadDataHandler
       
      );
    //  console.log(this.state.refreshing);
    };



    call = (number) => {
      //handler to make a call
      if(number)
      {
        const args = {
          number: number,
          prompt: false,
        };
        call(args).catch(console.error);
        
      }else{
        alert('Phone Number not found');
        return;
      }
      
     
    };

   


    /**
   * Render each item from Flatlist. If the record is
   * pending we should show a loading spinner.
   *
   * @method renderItem
   */
      renderRow=({item})=> {
       
      // console.log('status'+item.status);
      
        let badgeStatus=(
        
    

          <TouchableOpacity  onPress={()=>{this.PopupEvent(),this.setState({mobile_no:item.mobile_no})}}>
             

          <Card style={[style.card]}  >
              <CardItem header bordered first style={{margin:2,height:0.1}}  >
                <Icon name="cash" primary/>
                <Text style={[style.mini]}>Invoice Number - {item.order_no}</Text>
              </CardItem>
              <CardItem bordered style={{borderBottomWidth:0,height:40}}>
                   <Left >
                      <Icon name="car" style={[style.Icon]}/>
                      <Text style={[style.mini]}>
                              
                              {item.registration_no}
                      </Text> 
                    </Left>
                    <Right style={{marginLeft:'auto',marginRight:'auto'}}>
        
                    <Badge warning >
                        <Text style={{paddingLeft:3}}>
                              Draft
                        </Text> 
                    </Badge>
                      
            
            
                    </Right>
              </CardItem>

              <CardItem  style={{alignItems:'flex-start',flexDirection:'row'}}>
                <View style={{flexDirection:'row'}}>
    
                  <Icon name="person" style={[style.Icon]}/>
                  <Text style={[style.mini]}>
                          {item.customer}
                  </Text>
                </View>
                <View style={{marginLeft:'auto'}}>
            
                  <Text style={[style.mini,{paddingLeft:3,fontWeight:'bold',color:'black'}]}>
                       Total {item.total}
                  </Text> 
                </View>
          </CardItem>
          <CardItem  footer bordered last style={{alignItems:'flex-start',flexDirection:'row',borderBottomLeftRadius:5,borderBottomRightRadius:5}}>
   
                <View style={{marginLeft:'auto'}}>
                     <Text style={[style.mini,{paddingLeft:3,fontWeight:'bold',color:'#ff6263'}]}>
                       Pending amount   {item.balance}
                    </Text> 
           
                </View>
          </CardItem>
        </Card>       
        </TouchableOpacity>
        
        );

        return(
          badgeStatus
             );
    
    }


    
  _listEmptyComponent = () => {
    return (
        <CustomEmptyList content="No Job Invoice found" />
    );
}

PopupEvent=()=>{
  this.setState({ConfirmationPopup:!this.state.ConfirmationPopup});
}

Confirmation=()=>{
  console.log(this.state.mobile_no);
  this.PopupEvent()
 this.call(this.state.mobile_no);
}


     /**
   * Based on scroll position determine which card is in the current
   * viewport. From there you can set the impagination readOffset
   * equal to the current visibile card.
   *
   * @method handleLoadMore
   */
      handleLoadMore = () =>{

        if(!this.onEndReachedCalledDuringMomentum){
          
          this.setState({page:this.state.page+1,loadingContent:true},this.LoadDataHandler);
        
          console.log('handle load more',this.state.page);
        
        }
    }

    renderFooter=()=>{

      return(
        this.state.loadingContent?
        <View style={style.loader}>
            <ActivityIndicator/>
        </View>:null
      );

    }


    


    render() {
          
      
      const {  loading,InvoiceList,ConfirmationPopup,IsSearch,jobcard_no,customer_name,JobcardStatus,Search_status } = this.state;

      let ObjState={jobcard_no:jobcard_no,customer_name:customer_name,"JobcardStatus":JobcardStatus,"Search_status":Search_status}
     
     // console.log("navigation "+this.props.navigation.state.routeName);


      //  const uri = "https://facebook.github.io/react-native/docs/assets/favicon.png";
      if(!loading) { 
      return (
            <Container>
                        
                        <ConfirmBox  
                      PopupTitle="Can you call this customer?"
                      ModalVisible={ConfirmationPopup}
                      PopupEvent={()=>this.PopupEvent()}
                      ConfirmEvent={()=>this.Confirmation()}
                      ContentText={"kl"}
                      ConfirmationBoxText={"Yes"} 
                      CancelBoxText={"No"}

                      />


                          {(IsSearch)?(<Title al style={{ color: "#4c4c4c" }}>Search Results</Title>):null}  
                  

                          {/* {this.CustomSearch(this.props.navigation,ObjState,'UserList')}
                            */}
                           <SearchPopup props={this.props.navigation} SearchStates={ObjState}/>
                           
                           <FlatList
                              style={{flex:1,padding:5}}
                              contentContainerStyle={{ flexGrow: 1 }}
                              data={InvoiceList}
                              renderItem={this.renderRow}
                              keyExtractor={(item,index)=>index.toString()}
                              onEndReached={this.handleLoadMore}
                              onEndReachedThreshold={0.01}
                              onRefresh={this._handleRefresh}
                              refreshing={this.state.refreshing}
                              ListEmptyComponent={this._listEmptyComponent}
                              ListFooterComponent={this.renderFooter}
                            //  onMomentumScrollBegin={() => { this.onEndReachedCalledDuringMomentum = true; }}
                              onMomentumScrollEnd={()=>{
                                         this.onEndReachedCalledDuringMomentum = false;
                              }}
                            />
       
                   
            </Container>
        );
      }else{
        return (
          <Container>
                    
                  <Content>
                  <ActivityIndicator />
                  </Content> 
                  
        </Container>);
      }
    }
}
const style = StyleSheet.create({
  EmptyContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',

    height: '100%'
  },
    noBorder: {
     borderBottomWidth:0
    },
    colWidth: {
      flex:1
     },
     badgeColor: {
      backgroundColor:'#F97C2C'
    },
    
  card: {
    // flexDirection: 'row',
     height: undefined,
     width: undefined,
     // alignSelf: 'center',
     marginLeft:10,
     margin:5,
     borderRadius: 5
   },
  Icon: {
    color:'#929da9',
    fontSize: normalize(17),

  },
  mini: {
    fontSize: normalize(12),
  },
  small: {
    fontSize: normalize(16),
  },
  medium: {
    fontSize: normalize(17),
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
},
autocompleteContainer:{
 backgroundColor: '#ffffff',
 borderWidth: 0,
 
},

  });
module.export = UserList;