### WMS App -  Release June 2019

## Developed By Manimaran

## Requirement 
   RAM Memory minimum : 8GB
   
## Machine Configuration Steps
   Please follow the link  stepup the  machine
   https://zlxadhkust.wordpress.com/2017/07/06/setup-react-native-for-android-app-development-on-windows-part-1/  
   
   Further more details, Please refer the react-native offical document
   https://reactnative.dev/docs/getting-started 

## Installtion Steps
  
  
  1. Clone the project from repo
  2. In Project Root Folder, run the following command to install node packages
        ```npm install```
  3. After installed the NPM Packages,want to run the application
     Please run the following command
        ```react-native run-android```
  
 ##  Commands
     
1. Please clear the cache  using this command
 		``` npm start -- --reset-cache ``` 

2.  Clean Gradle in android folder.It removes the build directory.
             ``` gradlew clean ```

 ## Debugging Steps 
 
 1. In Android Emulator ,Click  ``` ctrl+ shift+B```. It show the options.
 2. Select ```Debug JS Remotely ``` option
 3. In chrome browser, automatically open debugging tab 
 4. Please change  this URL ```http://localhost:8081/debugger-ui/``` for debugging



  ## Errors

  1. If you got the following error, while build the app
    ```Unable to resolve module…``` 

     Its specificed error - invalid Component path or Component doesn't exist
 2.  If you find this type of error, while build the app
    ``` Execution failed for task: ```
   ```` java.io.IOException: Could not delete path````
              <br>  or <br>
   ``` Unable to delete directory  ```
      

    
    
   In project root folder, run the following app
    ``` cd android
        gradlew clean```

 3. If Display error like this format
    ```Could not resolve all dependencies 'App:DebugCompilePath'```
   ``` could not resolve 'Com android support support coreutils 27.1.*'```
   ``` could not find file support-core-utilus```
   <br>Solution:
            <br>in command
    
       ``` cd android```

       ```gradlew clean   ```

      if it could not be solved then try this command
    
    ```gradlew build --refresh-dependencies   ```


## Change End Point API URL
 In .env file , set ```API_URL=<url_name>/api```


## Build the unsigned APK - Testing Purpose Not for google Playstore

1. Following steps performed to build unsigned APK

    1. Please clear the cache  using this command
 		```npm start -- --reset-cache```
    2.   Clean Gradle in android folder
	  ```` $ cd android````
	 ````  $gradlew clean````
    3. Then Again try to Build 
	   ``react-native run-android``
    If working fine, go to build step

    ```$ cd android```
    ```$ ./gradlew assembleDebug```

    After Unsigned APK build run successfully.

    Please find the file from directory
    
    ```mobile_app_react\android\app\build\outputs\apk\debug\app-debug.apk```


   

