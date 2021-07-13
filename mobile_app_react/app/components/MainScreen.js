import React, {Component} from 'react';
import {
  Text,
  View,
  StyleSheet,
  Button
} from 'react-native';
import PropTypes from 'prop-types';
import Config from 'react-native-config';
class MainScreen extends Component {

  constructor(props) {
    super(props);
    this.openMenu = this.openMenu.bind(this);
  }

  openMenu(){
    this.props.navigation.openDrawer();
  }


  render () {
    return (
      <View style={styles.container}>
        <Text>Main</Text>
        <Button onPress={() => this.props.navigation.navigate("Detail")} title="Detail Page" />
      </View>
    );
  }
}

const styles = StyleSheet.create({
  container: {
      flex: 1,
      justifyContent: 'center',
      alignItems: 'center'
  }
});
MainScreen.propTypes = {
  navigation: PropTypes.object
}; 

export default MainScreen;