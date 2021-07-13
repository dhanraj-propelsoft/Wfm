import React, { Component } from 'react'
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
  Item} from 'native-base';
  import PropTypes from 'prop-types';
  import { View, Platform,Modal  } from "react-native";
  
  import PopupMenu  from './CustomPopup';


const ICON_SIZE = 24

export default class CustomHeader extends Component {
  static propTypes = {
    // array of strings, will be list items of Menu
    navigation:  PropTypes.arrayOf(PropTypes.string).isRequired,

  }

  onPopupEvent = (eventName, index) => {
  
   // const routeName=this.props.navigation.state.routeName;
    if (eventName !== 'itemSelected') return

    if (index === 0) {
      
        this.props.navigation.push('JobCardCreate')

      }else
      if (index === 1) {
      
        this.props.navigation.push('AddVehicle')

      }else {

      const setParamsAction=this.props.navigation.setParams({ isModalVisible: true });this.props.navigation.dispatch(setParamsAction);
    
    }
  }

  render () {
      
    const { navigation }= this.props;


    return (
        <Header  style={{ backgroundColor:'#F97C2C',flexDirection:'row',alignItems:'flex-start'}}>
        <View style={{width:'10%',marginTop:'auto',marginBottom:'auto'}}>
            
            {(navigation.state.routeName=='Popup')?
                <Icon name="md-close" style={{paddingLeft:10,color:'white'}} size={30}
                 onPress={ () =>navigation.goBack()}/>
                :
                 (navigation.state.routeName=='JobCardEdit'||navigation.getParam('SearchResult',false)==true)?
                  
                  <Icon name="arrow-back" style={{paddingLeft:10,color:'white'}} size={30}
                 onPress={ () =>(navigation.state.routeName=='JobCardEdit')?navigation.goBack():navigation.push(navigation.state.routeName)}/>:<Icon name="md-menu" style={{paddingLeft:10,color:'white'}} size={30}
                 onPress={ () => navigation.openDrawer()}/>}
             
        </View>
        <View style={{width:'80%',marginTop:'auto',marginBottom:'auto'}}>

              <Title>
               JobCard Manager
              </Title>
        </View>
        <View style={{width:'10%',marginTop:'auto',marginBottom:'auto'}}>

        {(navigation.state.routeName=='JobCardLst'||navigation.state.routeName=='UserList')
        ?
        <PopupMenu actions={['Add Jobcard','Add Vehicle','Search']} onPress={this.onPopupEvent}/>:
        <PopupMenu actions={['Add Jobcard','Add Vehicle']} onPress={this.onPopupEvent}/>
        
        }
              
            
          </View>
        
      </Header>
      
    )
  }


}