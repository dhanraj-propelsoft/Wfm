import React, { Component } from 'react';
import {
  StyleSheet,Platform,TouchableOpacity,TouchableWithoutFeedback,AsyncStorage,ActivityIndicator,Dimensions,PixelRatio ,AppState,FlatList
} from 'react-native';
import {Container, Content, List,ListItem,  Button,Input,
  Item, Text,View,Form, Thumbnail, Badge,Right,Left,Card, Label,
  CardItem, Body,Icon, Spinner, Title } from 'native-base';
import AppHeader from './appHeader';
import AppFooter from './appFooter';
import Config from 'react-native-config';
import {withNavigationFocus} from 'react-navigation';
import Dataset  from 'impagination';
import SearchPopup from './SearchPopup';
import axios from 'react-native-axios';
import CustomEmptyList from './CustomEmptyList';

import Modal from "react-native-modal";   
import { Dropdown } from 'react-native-material-dropdown';

import Autocomplete from 'react-native-autocomplete-input';

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
    console.log(query);
    const regex = new RegExp(`${query.trim()}`, 'i');
 
    return JobcardList.filter(item => item.name.search(regex) >= 0);
  
  }else{
    return [];
  }
}


export default class Home extends Component {
  constructor(props) {
    super(props);
    this.onEndReachedCalledDuringMomentum = true;

    this.state = {
      JobcardList:[],
      JobcardStatusList:[],
      JobcardStatus:'',
      token:null,
      PersonId:null,
      org_id:null,
      page:0,
      loading:true,
      loadingContent:false,
      IsSearch:false,

 
      //Search Terms
      jobcard_no:'',
      customer_name:'',
   
      Search_status:'',
      refreshing: false,
    

      //Autoc
      }
    };
  



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
           
          // console.log(this.state[state_type]);
    
          }



    /**
   * 
   * Date conversion DD/MM/Year
   *
   * @method formatDate
   */

  formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [ day, month,year].join('-');
}




  

  /**
   * 
   *
   * @method componentWillMount
   */


  componentWillMount() {
        
          //  this.setState({loadingContent:true},this.LoadDataHandler);
          console.log("componentWillMount?");
          
          const { navigation } = this.props;
          // TODO: After DID Changes
            console.log(this.state.IsSearch);
             navigation.setParams({ isModalVisible: false,ResetSearch: this._ResetSearch,HandleSearchInput:this._HandleSearchInput,HandleSearch:this._Search,IsSearchResult:this.state.IsSearch,SearchResult:navigation.getParam('SearchResult',false),CurrentPage:navigation.getParam('CurrentPage',false) });


         
           
          
        
          this.subs = [navigation.addListener('didFocus',(payload) =>{
            console.log("Inside Focus?");

              if(this.state.IsSearch)
              {
                console.log("Inside is Search Exist?");
                this.setState({loading:true,loadingContent:false,page:0,JobcardList:[],jobcard_no:'', customer_name:'',Search_status:'',IsSearch:false},this.LoadDataHandler,this.SetNavigationParams)
                
              }else{
                // TODO: After Did changes this.LoadDataHandler
                console.log("Inside is Not Search Exist?");
                 // console.log("did focus Working");
                this.setState({jobcard_no:'', customer_name:'',Search_status:'',IsSearch:false},this.LoadDataHandler,this.SetNavigationParams);
              }
            })];
      }

      
    SetNavigationParams=()=>{
         
      const { navigation } = this.props;

        navigation.setParams({ isModalVisible: false,ResetSearch: this._ResetSearch,HandleSearchInput:this._HandleSearchInput,HandleSearch:this._Search,IsSearchResult:this.state.IsSearch,SearchResult:false});

     }

      

        /**
        * @method LoadDataHandler
        * 
        * Retrieve the Data from API
        * 
        * We will call this function on ComponentDidMount or ComponentWillMount 
        */


      LoadDataHandler = async ()=> {
        console.log("Inside LoadData?");
        const { jobcard_no,customer_name,JobcardStatus }= this.state;
       

        const ApiToken=await  AsyncStorage.getItem('Token', (err, item) => item);
        const Organization_Id=await  AsyncStorage.getItem('org_id', (err, item) => item);
        const PersonId=await  AsyncStorage.getItem('PersonId', (err, item) => item);

        let pageSize=5;
        let page=this.state.page;
        
       // let URL_TEST=`https://serene-beach-38011.herokuapp.com/api/faker?page=${page}&per_page=${pageSize}`;
        
       let URL_PRO=`${API_URL}/wms_JobCardList`;

        console.log(URL_PRO);
        console.log({
          person_id:PersonId,
          org_id:Organization_Id,
          page:page,
          per_page:pageSize,
          jobcard_no:jobcard_no,
          customer_name:customer_name,
          job_status:JobcardStatus

         });

        axios.post(URL_PRO,{
          person_id:PersonId,
          org_id:Organization_Id,
          page:page,
          per_page:pageSize,
          jobcard_no:jobcard_no,
          customer_name:customer_name,
          job_status:JobcardStatus

         }, {
          headers: {
              Accept: 'application/json',
              'Content-Type': 'application/json',
              'Authorization':'Bearer '+ApiToken,
          }
        
      }).then(response =>{


           console.log(response);
           //return false;
           if(response.status==200){
          
            this.setState({
                 JobcardList: (page==0)?response.data.jobcardList:this.state.JobcardList.concat(response.data.jobcardList),
                 loading:false,
                 loadingContent:false,
                 JobcardStatusList:response.data.jobcardStatus,
                 token:ApiToken,
                 PersonId:PersonId,
                 org_id:Organization_Id,
                 refreshing: false

                });
                console.log(response);
              }

         })
          .catch((error) => {
            console.error(error);
          });
      }

       /**
       * Componentwillmount unset  the array of this.subs - property
       * it will be trigger after chaning the tab menu.
       * Purpose of re-rendering data from API in every click of this Tab
       * 
       * Search 
       *      in jobcard - 1 ,invoice - 2, both -3
       * @method Search
       */


      _Search = () =>{


        const { jobcard_no,IsSearch,customer_name,JobcardStatus,token,PersonId,org_id,page }= this.state;

        let pageSize=5;

        const searchStatus= (this.props.navigation.getParam("CurrentPage",false)==2)?3:1;
       
        this.props.navigation.setParams({
          SearchResult:true,
          CurrentPage:searchStatus
          
        });

        this.setState({ loading:true,loadingContent:false,page:0})
      
        
        const { navigation } = this.props;
        
        let URL_PRO=`${API_URL}/wms_JobCardList`;

        console.warn(URL_PRO);
       
       axios.post(URL_PRO,{
            person_id:PersonId,
            org_id:org_id,
            page:page,
            per_page:pageSize,
            jobcard_no:jobcard_no,
            customer_name:customer_name,
            job_status:JobcardStatus
            
           }, {
            headers: {
              Accept: 'application/json',
              'Content-Type': 'application/json',
              'Authorization':'Bearer '+token,
            }
            
          }).then(response =>{
            
            
           // console.log(response.data.jobcardList+  "Response Data");
            //return false;
            if(response.status==200){

              navigation.setParams({SearchResult:true});
             
              this.setState({

                   JobcardList:response.data.jobcardList,
                    loading:false,
                   loadingContent:false,
                   JobcardStatusList:response.data.jobcardStatus,
                   IsSearch:true,
                   refreshing: false
                 
  
                  });
                 // console.log(response.data.jobcardList);
                }
  
           })
            .catch((error) => {
              console.error(error);
            });
  
     //     console.log(this.state.JobcardList);

      }





      _handleRefresh = () => {

        this.setState(
          {
            page: 0,
            refreshing: true
          },
          
            this.LoadDataHandler
         
        );
       // console.log(this.state.refreshing);
      };

  
      /**
       * @method HandleOnPress
       * 
       * After click press event redirect the JobcardScreen With parameters
       * 
       * 
      */


      handleOnPress = (id,vehicle_id,organization_id) => {

          console.log(id,vehicle_id,organization_id);
          //console.log("working");
          if(id && vehicle_id && organization_id)
          {
              
                this.props.navigation.push("JobCardEdit", {id:id,vehicle_id:vehicle_id,organization_id:organization_id});
          }
      }


   


      // componentDidUpdate (previousProps) {
      //   console.log(previousProps.isFocused,this.props.isFocused);

      //   if (!previousProps.isFocused && this.props.isFocused) {
       
      //   }
      // }
 
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
       

          this.subs.forEach((sub) => {
            sub.remove();
          });
      }




  
  


  /**
   * Render each item from Flatlist. If the record is
   * pending we should show a loading spinner.
   *
   * @method renderItem
   */




  renderRow=({item})=> {
 
        if(item.length==0) return null;
        
        return(

          <TouchableWithoutFeedback onPress={this.handleOnPress.bind(this,item.id,item.vehicle_id,item.organization_id)} >
                        <Card style={[style.card]}  >
                          <CardItem header bordered first style={{margin:2,height:0.1}}  >
                            <Icon name="card"  primary/>
                            <Text style={[style.mini]}>{item.order_no}</Text>
                          </CardItem>
                          <CardItem bordered style={{borderBottomWidth:0,height:0.1}}>
                            <Left>
                                <Icon name="car" style={[style.Icon]}/>
                                <Text style={[style.mini]}>
                                        
                                        {item.registration_no}
                                </Text> 
                            </Left>
                            <Right style={{alignItems:'flex-start',flexDirection:'row'}}>
                                  <Icon name="person" style={[style.Icon]}/>
                                  <Text style={[style.mini,{paddingLeft:3}]}>
                                        {item.customer}
                                  </Text>
                                  
                                  
                            </Right>
                          </CardItem>
                          <CardItem style={{height:0.1}} >
                            <Left>
                                <Icon name="calendar" style={[style.Icon]}/>
                                <Text style={[style.mini]}> 
                                        
                                        {item.job_date?this.formatDate(item.job_date):null}
                                </Text> 
                            </Left>
                            <Right style={{alignItems:'flex-start',flexDirection:'row'}}>
                                  <Icon name="calendar" />
                                  <Text style={[style.mini,{paddingLeft:3}]}>
                                        {item.job_completed_date?this.formatDate(item.job_completed_date):null}
                                  </Text>
                                  
                                  
                            </Right>
                          </CardItem>
                          <CardItem last >
                            <Left>
                          <Icon name="build" style={[style.Icon]}/>
                          <Icon name="person" style={[style.Icon]}/>
                                <Text style={[style.mini]}>
                                        
                                        {item.assigned_to}
                                </Text> 
                            </Left>
                            <Right style={{alignItems:'flex-start',flexDirection:'row'}}>
                                  
                                  <Badge primary >
                                        <Text style={{paddingLeft:3}}>
                                              {item.jobcard_status}
                                        </Text> 
                                  </Badge>
                                  
                                  
                            </Right>
                          </CardItem>
                        </Card>
          </TouchableWithoutFeedback>
        );
     
  }
  


  _listEmptyComponent = () => {
        return (
            <CustomEmptyList content="No Job Card found" />
        );
    }

  /**
   * Based on scroll position determine which card is in the current
   * viewport. From there you can set the impagination readOffset
   * equal to the current visibile card.
   *
   * @method handleLoadMore
   */
    handleLoadMore = () =>{
    //  console.log(this.onEndReachedCalledDuringMomentum+"true");
          if(!this.onEndReachedCalledDuringMomentum){
           
            this.setState({page:this.state.page+1,loadingContent:true},this.LoadDataHandler);
          // this.onEndReachedCalledDuringMomentum = true;
          
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
    const {  loading,JobcardList,IsSearch,jobcard_no,customer_name,JobcardStatusList,JobcardStatus,Search_status } = this.state;

  //  console.log("Data:"+this.props.navigation.getParam('isModalVisible'));


    let ObjState={jobcard_no:jobcard_no,customer_name:customer_name,"JobcardStatusList":JobcardStatusList,"JobcardStatus":JobcardStatus,"Search_status":Search_status}
  //  let SearchWindow=(<SearchPopup props={this.props.navigation} SearchStates={ObjState}  route='JobCardLst'/>);
          
    console.log(ObjState);
    /*
      * Generate ObjState using jobcard_no and customer_name
      *
      * ** */

    if(!loading) { 
    return (
      <Container  >
     
      {/*   <Content contentContainerStyle={{flex: 1}} padder scrollEventThrottle={300} onScroll={this.setCurrentReadOffset} removeClippedSubviews={true}>
        */}     
          {(IsSearch)?(<Title al style={{ color: "#4c4c4c" }}>Search Results</Title>):null}  
           
            {/* // {this.CustomSearch(this.props.navigation,ObjState,true)} */}
            <SearchPopup props={this.props.navigation} SearchStates={ObjState}/>
             <FlatList
              contentContainerStyle={{flexGrow: 1,padding:30}}
              style={{flex:1,padding:5}}
              data={JobcardList}
              renderItem={this.renderRow}
              contentContainerStyle={{ flexGrow: 1 }}
              keyExtractor={(item,index)=>index.toString()}
              onEndReached={this.handleLoadMore}
              onEndReachedThreshold={0.01}
              ListEmptyComponent={this._listEmptyComponent}
              ListFooterComponent={this.renderFooter}
              onRefresh={this._handleRefresh}
              refreshing={this.state.refreshing}
          //    onMomentumScrollBegin={() => { this.onEndReachedCalledDuringMomentum = true; }}
              onMomentumScrollEnd={()=>{
                this.onEndReachedCalledDuringMomentum = false;
                    }}
            />
        {/* </Content> */}
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


const style= StyleSheet.create({
  pullRight:{
       flexDirection: 'row', 
       alignItems: 'center'
  },
  card: {
    // flexDirection: 'row',
     height: undefined,
     width: undefined,
     // alignSelf: 'center',
    // padding:10,
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
    fontSize: normalize(15),
  },
  medium: {
    fontSize: normalize(17),
  },
  loader:{
    marginTop:10,
    alignItems:'center'
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
