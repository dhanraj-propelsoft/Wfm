/**
 * Sample React Native App
 * https://github.com/facebook/react-native
 *
 * @format
 * @flow
 */

import React, {Component} from 'react';
import {Platform, StyleSheet,  View ,Dimensions,ScrollView,NativeModules,UIManager,findNodeHandle,TouchableOpacity  } from 'react-native';
import { Container,
  Header,
  Title,
  Content,
  Button,
  Icon,
  ListItem,
  Text,
  Input,
  Badge,
  Left,
  Right,
  Body,
  Switch,
  Radio,
  Item,
  Picker,
  Separator,Label, Fab,Root} from 'native-base';
import {
    createStackNavigator,
    createAppContainer,createDrawerNavigator,createSwitchNavigator,createBottomTabNavigator
  } from 'react-navigation';
  //  import MainScreen from '../components/Dashboard_WMS';
  // import JobcardList from '../components/Jobcard';
  // import ProjectDetailsscreen from '../components/ProjectDetails';
  //  import UserListscreen from '../components/UserList';
  // import TaskListscreen from '../components/TaskList';
  // import NewSearch from '../components/NewSearch';
  // import Navigator from '../components/Navigation';
  // import SplashScreen from '../components/SplashScreen';
  // import AuthLoading from '../components/AuthLoading';
  // import Login from '../components/Login';
  // import JobCardCreate from '../components/JobCardCreate';
  
  // import AddVehicle from '../components/AddVehicle';
  // import VehicleList from '../components/VehicleList';
  // import JobCardEdit from '../components/JobCardEdit.js';
  // import UserAccount from '../components/UserAccount';
  // import Icons from 'react-native-vector-icons/Ionicons';
  // import CustomHeader from '../components/CustomHeader';
  // import CustomHeader2 from '../components/CustomHeader2';
  // import Search from '../components/CustomSearchBar/DropDown';

  /*  */

  import MainScreen from '../components/Dashboard_WMS';
  import JobcardList from '../components/Jobcard';
   import UserListscreen from '../components/UserList';
  import Navigator from '../components/Navigation/Navigation';
  import SplashScreen from '../components/SplashScreen';
  import AuthLoading from '../components/AuthLoading';
  import Login from '../components/Login';
  import JobCardCreate from '../components/JobCardCreate';
  
  import AddVehicle from '../components/AddVehicle';
  import VehicleList from '../components/VehicleList';
  import JobCardEdit from '../components/JobCardEdit.js';
  import UserAccount from '../components/UserAccount';
  import Icons from 'react-native-vector-icons/Ionicons';
  import CustomHeader from '../components/CustomHeader';
  import CustomHeader2 from '../components/CustomHeader2';
  import Search from '../components/CustomSearchBar/DropDown';


  //import Navigation from './Route';
  import Menu, { MenuItem, MenuDivider } from 'react-native-material-menu';



 // const AppNav = createAppContainer(Navigation);
 console.disableYellowBox = true;
 class App extends Component {

  
 render(){
  return (
    <Root>

      <AppContainer navigation={this.props.navigation} />
    </Root>
  );

}
}




const JobCard = createStackNavigator({
  JobCardLst:{
    screen:JobcardList,
    navigationOptions:({navigation}) => {
      
      //navigation.setParams({SearchResult:false});
      
      return {  

      header:(
        <CustomHeader2 navigation={navigation} />
      ),
 
      }
    }
  },
  JobCardCreate:{

    screen:JobCardCreate,
     navigationOptions:({navigation}) => {
     
      return {  
      
        header:(
          <CustomHeader2 navigation={navigation} />
        ),
      }
    }
  },

  JobCardEdit:{
    screen:JobCardEdit,
    navigationOptions:({navigation}) => {
     
      return {  
      
        header:(
          <CustomHeader2 navigation={navigation} />
        ),
   
      }
    }
  },


      Popup:{
        screen:AddVehicle,
        navigationOptions:({navigation}) => {
         
          const{ params={}}=navigation.state
          /* 
          Hence Add Vehicle Screen Save  Function as a parameter

         
         */



          return {  
          

          header:(
            <CustomHeader2 navigation={navigation} />
          ),
          
         
        
          }
        }
      },
      Search:{
        screen:Search,
        navigationOptions:({navigation}) => {
         
          const{ params={}}=navigation.state
          /* 
          Hence Add Vehicle Screen Save  Function as a parameter

         
         */



          return {  
          

          header:(
            <CustomHeader2 navigation={navigation} />
          ),
          
         
        
          }
        }
      },

     
 
},{
  mode:'modal' 
}
);

_menu = null; 



  setMenuRef = ref => {
    this._menu = ref;
  };
 
  hideMenu = () => {
    
    this._menu.hide();
    
     
    
  };
 


  showMenu = () => {
    this._menu.show();
  };


const DashboardNavi= createStackNavigator({
    UserAccounts:{
   screen:UserAccount,
   navigationOptions:({navigation}) => {
    
     return {  
       header: null

       
 
     }
   }
 },

  Dashboard:{
    screen:MainScreen,
    
    navigationOptions:({navigation}) => {
     
      return {  
      
        header:(
         <CustomHeader2 navigation={navigation} />
        ),
   
      }
    }
  },

});

DashboardNavi.navigationOptions=({navigation })=>{
  let tabBarVisible = true;
 
    let routeName = navigation.state.routes[navigation.state.index].routeName
    
     console.log(routeName,tabBarVisible);
    if ( routeName == 'UserAccounts' ) {
        tabBarVisible = false
       // headerMode='none';
    }

    return {
        tabBarVisible,
      //  headerMode: headerMode
    }
}



 const DueInvoice = createStackNavigator({
  UserList:{
    screen:UserListscreen,
    navigationOptions:({navigation}) => {
     
      return {  


          header:(
           <CustomHeader2 navigation={navigation} />
          ),
          Title:'Due Invoice'
      }
    }
  },
  

},{
}
);

const Vehicle = createStackNavigator({
  User:{
    screen:VehicleList,
    navigationOptions:({navigation}) => {
     
      return {  
      
  

          header:(
           <CustomHeader2 navigation={navigation} />
          ),
      }
    }
  },
   AddVehicle:{
    screen:AddVehicle,
    navigationOptions:({navigation}) => {
      
     
      return {  
        header:(
          <CustomHeader2 navigation={navigation} />
        ),
   
      }
    }
  },
},{
 
}
);
const getTabBarIcon = (navigation,focused,tintColor) =>{
  const {routeName } = navigation.state;
  let IconComponent = Icon;
  let iconName;
  let iconColor;
  let iconFont;
  iconFont={fontSize:20};
  if(focused){
    iconColor={color: '#F97C2C' };
  }else{
    iconColor={color: '#929da9' };
  } 
  
  //const route = navigationState.routes[navigationState.index];
 
  if(routeName ==='Dashboard'){
    iconName =`md-home`;
    
  } else if(routeName ==='JobCard' ) {
    console.log(routeName);
    iconName =`card`;
    
   }else if(routeName ==='DueInvoice') {
    iconName =`list-box`;
    } else{
    iconName =`car`;
  }
 return <IconComponent name={iconName}  style={[iconColor,iconFont]} />;
};

const DashboardTabNavigator = createBottomTabNavigator({


  Dashboard:{
    screen:DashboardNavi
 
  },
  JobCard,
  DueInvoice,
  Vehicle
},{
  navigationOptions:({navigation}) => {
  
    const { routeName } = navigation.state.routes[navigation.state.index];
    
    return { header:null,  tabBarIcon:({focused,tintColor})=>getTabBarIcon(navigation,focused,tintColor),
    tabBarOnPress: (scene, jumpToIndex) => {
      console.log('onPress:', scene.route);
      jumpToIndex(scene.index);
    },
    }
   
},
defaultNavigationOptions:({navigation}) =>({
  
    // tabBarLabel:{focused,tintColor})=>
   // tabBarLabel:({focused,tintColor})=> {(  navigation.state.routes[navigation.state.index] ==='Invoice')?'Due Invoice': navigation.state.routes[navigation.state.index]},
    tabBarIcon:({focused,tintColor})=>getTabBarIcon(navigation,focused,tintColor),
    tabBarOnPress: ({ navigation, defaultHandler }) => {
      let parentNavigation = navigation.dangerouslyGetParent();
        let prevRoute = parentNavigation.state.routes[parentNavigation.state.index];
        let nextRoute = navigation.state;
        console.log({ prevRoute, nextRoute });
        defaultHandler();
    },
   // tabBarIcon:({focused,tintColor})=>getTabBarIcon(navigation,focused,tintColor)
  }), 

tabBarOptions:{
  activeTintColor: '#F97C2C',
  inactiveTintColor: '#929da9',
 labelStyle: {
    fontSize: 10,
  },
}
});







const DashboardStackNavigator = createStackNavigator({
  DashboardTabNavigator:{
    screen:DashboardTabNavigator
  },
 },{
   defaultNavigationOptions:({navigation})=>{
     return {
       headerLeft:<Icon name="md-menu" style={{paddingLeft:10}} size="30" onPress={ () => navigation.openDrawer()}/>
       

         
        }
   }
 }
 );





const AppDrawNav = createDrawerNavigator({

  Dashboard:{
    screen:DashboardStackNavigator
  },
  AddVehicle:{
    screen:AddVehicle
  }
},{
  initialRouteName:'Dashboard',
  contentComponent: Navigator,

});


const AppSwitchNavigator = createSwitchNavigator({
  Splash:{
    screen:SplashScreen
  },
  Auth: { screen: Login },
  AuthLoading:AuthLoading,
  App:{
   screen:AppDrawNav
 }
 
 },
 {
   
   defaultNavigationOptions: {
    headerStyle: {
      backgroundColor: '#f4511e',
    },
    headerTintColor: '#fff',
    headerTitleStyle: {
      fontWeight: 'bold',
    },
    initialRouteName:AuthLoading
  },
 });

const AppContainer = createAppContainer (AppSwitchNavigator);

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#F5FCFF',
  },
  welcome: {
    fontSize: 20,
    textAlign: 'center',
    margin: 10,
  },
  instructions: {
    textAlign: 'center',
    color: '#333333',
    marginBottom: 5,
  },
});
export default App;