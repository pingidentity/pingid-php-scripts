#pingid-php-scripts

### Overview

Collection of sample PHP scripts to demonstrate the PingID User Management API.

### System Requirements / Dependencies

Requires:
 - PingOne account with PingID service enabled (visit [Ping Identity Developer Site] to get a developer account)
 
### Installation

1. Enable 3rd-party Client Integration in the PingOne Admin Web GUI (Setup -> PingID -> Client Integration -> Third-party Clients -> Enable).
2. Download the `pingid.properties` file from PingOne (Setup -> PingID -> Client Integration -> Settings File -> Download) and put in in the scripts directory.
3. Execute the scripts from your command line i.e. `php getUserDetails.php jsmith`

### Workflows

1a. Enroll user with mobile device  
- `php addUser jdoe John Doe hzandbelt+johndoe@pingidentity.com`  
<pickup pairing key or QR code URL from output>  
- `php getUserDetails.php jdoe`  

1b. Add e-mail OTP  
- `php startOfflinePairing.php jdoe EMAIL hzandbelt+johndoe@pingidentity.com`  
- `php finalizeOfflinePairing.php <session-id> <otp>`  

2a. Enroll user with e-mail OTP  
- `php addUser.php jdoe John Doe hzandbelt+johndoe@pingidentity.com false`  
- `php startOfflinePairing.php jdoe EMAIL hzandbelt+johndoe@pingidentity.com`  
- `php finalizeOfflinePairing.php <session-id> <otp>`  

2b. Add mobile device later  
- `php getActivationCode.php jdoe`  
<pickup pairing key or QR code URL from output>  
- `php getPairingStatus.php <activation-code>`

### Disclaimer

This software is open sourced by Ping Identity but not supported commercially as such. Any questions/issues should go to the Github issues tracker or discuss on the [Ping Identity developer communities] . See also the DISCLAIMER file in this directory.

[Ping Identity developer communities]: https://community.pingidentity.com/collaborate
[Ping Identity Developer Site]: https://developer.pingidentity.com/connect
