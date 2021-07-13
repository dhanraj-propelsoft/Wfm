const React = require("react-native");
const { Platform, Dimensions } = React;

const deviceHeight = Dimensions.get("window").height;
const deviceWidth = Dimensions.get("window").width;

export default {
  drawerCover: {
    alignSelf: "stretch",
    height: deviceHeight / 3.5,
    width: null,
    position: "relative",
    marginBottom: 10
  },
  SidebarTitle: {
    position: "absolute",
    left: Platform.OS === "android" ? deviceWidth / 13 : deviceWidth / 12,
    top: Platform.OS === "android" ? deviceHeight / 9 : deviceHeight / 8,
    color:'white',
   
    fontSize:20
  },
  SidebarSubtitle: {
    position: "absolute",
    left: Platform.OS === "android" ? deviceWidth / 13 : deviceWidth / 12,
    top: Platform.OS === "android" ? deviceHeight / 6 : deviceHeight / 5,
    color:'white',
   
   
  },
  text: {
    fontWeight: Platform.OS === "ios" ? "500" : "400",
    fontSize: 16,
    marginLeft: 20
  },
  badgeText: {
    fontSize: Platform.OS === "ios" ? 13 : 11,
    fontWeight: "400",
    textAlign: "center",
    marginTop: Platform.OS === "android" ? -3 : undefined
  }
};