
Name: app-user-dropbox
Epoch: 1
Version: 2.1.1
Release: 1%{dist}
Summary: Dropbox
License: GPLv3
Group: ClearOS/Apps
Source: %{name}-%{version}.tar.gz
Buildarch: noarch
Requires: %{name}-core = 1:%{version}-%{release}
Requires: app-base
Requires: app-accounts

%description
Dropbox is a cloud-based file storage and synchronization service.  Use this app to synchronize files to a folder located in your home directory which can then be accessed by any device associated to the same Dropbox account (laptop, mobile, tablet etc.).

%package core
Summary: Dropbox - Core
License: LGPLv3
Group: ClearOS/Libraries
Requires: app-base-core
Requires: app-accounts-core
Requires: app-dropbox-core
Requires: app-user-dropbox-plugin-core

%description core
Dropbox is a cloud-based file storage and synchronization service.  Use this app to synchronize files to a folder located in your home directory which can then be accessed by any device associated to the same Dropbox account (laptop, mobile, tablet etc.).

This package provides the core API and libraries.

%prep
%setup -q
%build

%install
mkdir -p -m 755 %{buildroot}/usr/clearos/apps/user_dropbox
cp -r * %{buildroot}/usr/clearos/apps/user_dropbox/

install -D -m 0644 packaging/user_dropbox.acl %{buildroot}/var/clearos/base/access_control/authenticated/user_dropbox

%post
logger -p local6.notice -t installer 'app-user-dropbox - installing'

%post core
logger -p local6.notice -t installer 'app-user-dropbox-core - installing'

if [ $1 -eq 1 ]; then
    [ -x /usr/clearos/apps/user_dropbox/deploy/install ] && /usr/clearos/apps/user_dropbox/deploy/install
fi

[ -x /usr/clearos/apps/user_dropbox/deploy/upgrade ] && /usr/clearos/apps/user_dropbox/deploy/upgrade

exit 0

%preun
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-user-dropbox - uninstalling'
fi

%preun core
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-user-dropbox-core - uninstalling'
    [ -x /usr/clearos/apps/user_dropbox/deploy/uninstall ] && /usr/clearos/apps/user_dropbox/deploy/uninstall
fi

exit 0

%files
%defattr(-,root,root)
/usr/clearos/apps/user_dropbox/controllers
/usr/clearos/apps/user_dropbox/htdocs
/usr/clearos/apps/user_dropbox/views

%files core
%defattr(-,root,root)
%exclude /usr/clearos/apps/user_dropbox/packaging
%dir /usr/clearos/apps/user_dropbox
/usr/clearos/apps/user_dropbox/deploy
/usr/clearos/apps/user_dropbox/language
/var/clearos/base/access_control/authenticated/user_dropbox
