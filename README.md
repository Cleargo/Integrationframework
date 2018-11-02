# Integrationframework

## How to test config repeatedly?

### 1) Uninstall the extension (including delete workflow_* tables)
```
php bin/magento cleargo_integrationframeworks:testintegration
```
### 2) After uninstall, modify setup script *Setup/UpgradeData.php* to ensure config data is correct. Run setup upgrade to install it again
```
php bin/magento se:up
```

### 3) Run the workflow plan:
```
php bin/magento cleargo_integrationframeworks:executeSchedule
```
If the workflow_plan is in "pending", it will run automatically. It run the function the same as cronjob.


## Development notes
1. If you add new component or schedule, remember to add the default config data in setup script instead of directly modifing DB data.
2. Make sure your implementation is generic enough to merge to master. If you have some hardcode logic for specific logic which cannot fit by configuration, open a seperated component. Or open a new branch and never merge to master.
