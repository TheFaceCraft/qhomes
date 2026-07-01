# GitHub Actions Deployment Setup

## Frontend Deployment

The frontend automatically deploys to `https://qhomesfrontend.tfcmockup.com/` when changes are pushed to the `main` branch in the `frontend/` directory.

## Required GitHub Secrets

You need to configure the following secrets in your GitHub repository:

### How to Add Secrets:
1. Go to your GitHub repository: `https://github.com/TheFaceCraft/qhomes`
2. Click **Settings** → **Secrets and variables** → **Actions**
3. Click **New repository secret**
4. Add each of the following secrets:

### Required Secrets:

| Secret Name | Description | Example Value |
|------------|-------------|---------------|
| `FTP_SERVER` | FTP server hostname | `ftp.tfcmockup.com` or `tfcmockup.com` |
| `FTP_USERNAME` | FTP username | `u706445394` or your cPanel username |
| `FTP_PASSWORD` | FTP password | Your FTP account password |

## Deployment Configuration

- **Trigger**: Automatically on push to `main` branch (frontend folder changes)
- **Manual Trigger**: Available via GitHub Actions tab → "Deploy Frontend" → "Run workflow"
- **Build Output**: `frontend/build/`
- **Deploy Target**: `/home/u706445394/domains/tfcmockup.com/public_html/qhomesfrontend/`

## Testing the Deployment

1. Make a change to any file in the `frontend/` directory
2. Commit and push to the `main` branch
3. Go to the **Actions** tab in GitHub to see the deployment progress
4. Once complete, visit `https://qhomesfrontend.tfcmockup.com/` to see your changes

## Troubleshooting

- If the build fails, check the Actions tab for error logs
- Verify all secrets are correctly configured
- Ensure the FTP server path is correct
- Check that the FTP user has write permissions to the target directory

## Manual Deployment

You can manually trigger a deployment from GitHub:
1. Go to **Actions** tab
2. Click **Deploy Frontend to qhomesfrontend.tfcmockup.com**
3. Click **Run workflow** → Select `main` branch → **Run workflow**
